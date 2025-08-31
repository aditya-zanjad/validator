<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\ErrorManagerInterface;
use AdityaZanjad\Validator\Interfaces\InputManagerInterface;
use AdityaZanjad\Validator\Interfaces\MandatoryRuleInterface;

/**
 * @version 1.0
 */
class Validator
{
    /**
     * Useful in deciding whether or not to stop the validator on first failure.
     *
     * @var bool $stopOnFail
     */
    protected bool $stopOnFail = false;

    /**
     * To check whether the validation has been already performed or not.
     *
     * @var bool $validated
     */
    protected bool $validated = false;

    /**
     * @param   \AdityaZanjad\Validator\Interfaces\InputManagerInterface                                                                                                                                                $input
     * @param   array<string, mixed>  $rules
     * @param   \AdityaZanjad\Validator\Interfaces\ErrorManagerInterface                                                                                                                                                $errors
     * @param   array<string, string>                                                                                                                                                                                   $messages
     */
    public function __construct(protected InputManagerInterface $input, protected array $rules, protected ErrorManagerInterface $errors, protected array $messages = [])
    {
        $this->rules = $this->transformRules($this->rules);
    }

    /**
     * Reorganize/Preprocess the given validation rules before they are actually evaluated.
     *
     * @param array<int|string, mixed> $givenRules
     *
     * @return array<string, mixed>
     */
    protected function transformRules(array $givenRules): array
    {
        $actualPaths = $this->input->paths();

        foreach ($givenRules as $field => $rules) {
            $field  =   (string) $field;
            $rules  =   $this->parseFieldRules($field, $rules);

            // If the field to be validated does not contain any wildcard parameters.
            if (!\str_contains($field, '*')) {
                $givenRules[$field] = $rules;
                continue;
            }

            $fieldParams = \explode('.', $field);

            // For each given rule, loop through input paths array to determine the matching paths.
            foreach ($actualPaths as $actualPath) {
                $actualPathParams   =   \explode('.', $actualPath);
                $resolvedPath       =   '';

                /**
                 * Perform the matching of the wildcard path with each individual input array path. If the 
                 * actual path exceeds input path, strip its exceess to match with the wildcard path. If
                 * the actual path is lesser than the wildcard path, add necessary parameters to it to
                 * complete it.
                 */
                foreach ($fieldParams as $fieldIndex => $fieldParam) {
                    $actualPathParams[$fieldIndex] ??= 0;

                    // If the actual path parameters array is shorter than wildcard parameters path,
                    // fill up the path with the remaining parameters to complete it.
                    if (!\in_array($fieldParam, [$actualPathParams[$fieldIndex], '*'])) {
                        $remainingParts =   \implode('.', \array_slice($fieldParams, $fieldIndex));
                        $remainingParts =   \str_replace(['*.', '.*.', '.*'], ['0.', '.0.', '.0'], $remainingParts);
                        $resolvedPath   =   "{$resolvedPath}{$remainingParts}";

                        break;
                    }

                    // Replace the wildcard path parameter with its corresponding actual path parameter.
                    $resolvedPath .= "{$actualPathParams[$fieldIndex]}.";
                }

                // Remove any unneeded characters from the resolved path string.
                $resolvedPath = \rtrim($resolvedPath, '.');

                // If the resolved path does not exist in the rules array, we're fine to skip to the next iteration.
                if (!isset($givenRules[$resolvedPath])) {
                    $givenRules[$resolvedPath] = $rules;
                    continue;
                }

                // If the resolved path exists in the array, we need to combine the current rules with its existing rules while avoiding the duplicates.
                $givenRules[$resolvedPath]  =   $this->parseFieldRules($resolvedPath, $givenRules[$resolvedPath]);
                $givenRules[$resolvedPath]  =   \array_merge($givenRules[$resolvedPath], $rules);
                $givenRules[$resolvedPath]  =   \array_unique($givenRules[$resolvedPath]);
            }

            unset($givenRules[$field]);
        }

        return $givenRules;
    }

    /**
     * Transform the given field rules if necessary.
     *
     * @param   string  $field
     * @param   mixed   $rules
     * 
     * @return  array
     */
    protected function parseFieldRules(string $field, mixed $rules): array
    {
        if (\is_string($rules)) {
            $rules = \explode('|', $rules);
        }

        if (!\is_array($rules)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] must have validation rules specified either as a [STRING] or [INDEXED ARRAY].");
        }

        return $rules;
    }

    /**
     * Perform the validation process.
     *
     * @throws \Exception
     *
     * @return static
     */
    public function validate(): static
    {
        // Do not allow performing the same validation more than once.
        if ($this->validated) {
            throw new Exception("[Developer][Exception]: The validation for this instance has already been done. Create a new validator instance to perform a new validation.");
        }

        foreach ($this->rules as $field => $rulesGroup) {
            $field = (string) $field;

            foreach ($rulesGroup as $index => $rule) {
                $ruleDataType = \gettype($rule);

                $evaluation = match ($ruleDataType) {
                    'string'    =>  $this->evaluateRuleFromString($rule, $field, $index),
                    'object'    =>  $this->evaluateRuleFromObject($rule, $field, $index),
                    default     =>  throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid rule at the index [{$index}].")
                };

                // If the validation passes.
                if ($evaluation['result'] === true) {
                    continue;
                }

                // Prepare the validation error message
                $ruleName   =   $ruleDataType === 'object' ? Rule::keyOf($rule) : $rule;
                $message    =   !\is_null($ruleName) && isset($this->messages["{$field}.{$ruleName}"]) ? $this->messages["{$field}.{$ruleName}"] : $evaluation['instance']->message();
                $message    =   \str_replace(':{field}', $field, $message);

                // Add the validation error message.
                $this->errors->add($field, $message);

                if ($this->stopOnFail) {
                    break 2;
                }
            }
        }

        $this->validated = true;
        return $this;
    }

    /**
     * Stop the validation process immediately on the first validation failure.
     *
     * @param bool $stopOnFail
     *
     * @return static
     */
    public function abortOnFail(bool $stopOnFail = true): static
    {
        $this->stopOnFail = $stopOnFail;
        return $this;
    }

    /**
     * Evaluate the rule when it is provided in a string format.
     *
     * @param   string      $rule
     * @param   string      $field
     * @param   int|string  $index
     *
     * @throws  \Exception
     *
     * @return  array<string, bool|\AdityaZanjad\Validator\Base\AbstractRule>
     */
    protected function evaluateRuleFromString(string $rule, string $field, int|string $index): array
    {
        if (empty($rule)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule}] at the index [{$index}] for field [{$field}] must not be empty.");
        }

        // Extract the important data from the given parameters to execute the validation rule.
        $rule           =   \explode(':', $rule);
        $ruleClassName  =   Rule::valueOf($rule[0]);

        if (\is_null($ruleClassName)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$rule[0]}] at the index [{$index}].");
        }

        /**
         * The validation will be aborted if both of these conditions are true:
         * 
         *  [1] The input is equal to NULL.
         *  [2] The rule to be evaluated is not set to be run mandatorily.
         */
        if ($this->input->isNull($field) && !\in_array(MandatoryRuleInterface::class, \class_implements($ruleClassName))) {
            return ['result' => true];
        }

        // Prepare the arguments that'll be passed to the rule constructor.
        $arguments = isset($rule[1]) ? \preg_split('/(?<!\\\\),/', $rule[1]) : [];
        $arguments = \array_map(fn($arg) => \str_replace('\\,', ',', $arg), $arguments);

        // Instantiate the rule class to perform the validation.
        $instance = new $ruleClassName(...$arguments);

        // Perform the actual validation & return its result.
        return [
            'result'    =>  $instance->setInput($this->input)->check($field, $this->input->get($field)),
            'instance'  =>  $instance
        ];
    }

    /**
     * Evaluate the validation rule when it is provided as an object.
     *
     * @param   object      $rule
     * @param   string      $field
     * @param   int|string  $index
     *
     * @throws  \Exception
     *
     * @return  array<string, null|bool|\AdityaZanjad\Validator\Base\AbstractRule>
     */
    protected function evaluateRuleFromObject(object $rule, string $field, int|string $index): array
    {
        /**
         * After much thought or not so much thought I guess, unlike Laravel, I've decided
         * to keep the callback validation rule as 'implicit/requisite'. It means that
         * the callback validation will always be run regardless of whether the
         * input field is present (NOT NULL) or not (NULL).
         */
        if (\is_callable($rule)) {
            $rule = new Callback($rule);

            return [
                'result'    =>  $rule->setInput($this->input)->check($field, $this->input->get($field)),
                'instance'  =>  $rule
            ];
        }

        // The validation rule object should always end as an object of the 'AbstractRule' class no matter what.
        if (!$rule instanceof AbstractRule) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule specified the index [{$index}].");
        }

        /**
         * The validation will be performed only when either of these are 'true':
         *  [1] The input is NOT NULL.
         *  [2] The rule implements the 'MandatoryRuleInterface' interface.
         */
        if ($this->input->isNull($field) && !$rule instanceof MandatoryRuleInterface) {
            return ['result' => true];
        }

        return [
            'result'    =>  $rule->setInput($this->input)->check($field, $this->input->get($field)),
            'instance'  =>  $rule
        ];
    }

    /**
     * Check whether the validation was successful or not.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->errors->empty();
    }

    /**
     * Get an instance of the class that holds & manages the error messages.
     *
     * @return \AdityaZanjad\Validator\Interfaces\ErrorManagerInterface
     */
    public function errors(): ErrorManagerInterface
    {
        return $this->errors;
    }
}
