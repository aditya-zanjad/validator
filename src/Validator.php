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
        //
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

        $actualPaths = $this->input->paths();

        foreach ($this->rules as $field => $rules) {
            // Parse the input path & its validation rules before evaluating them.
            $field = (string) $field;

            if (\is_string($rules)) {
                $rules = \explode('|', $rules);
            }

            if (!\is_array($rules)) {
                throw new Exception("[Developer][Exception]: The field [{$field}] must have validation rules specified either as a [STRING] or [INDEXED ARRAY].");
            }

            if (!\str_contains($field, '*')) {
                $this->evaluateFieldRules($field, $rules);
                continue;
            }

            $fieldParams = \explode('.', $field);

            // For each given rule, loop through input paths array to determine the matching paths.
            foreach ($actualPaths as $actualPath) {
                $resolvedPath           =   '';
                $actualPathParams       =   \explode('.', $actualPath);
                $resolvedPathIsEmpty    =   true;

                /**
                 * Perform the matching of the wildcard path with each individual input array path. If 
                 * the actual path exceeds input path, strip its exceess to match with the wildcard 
                 * path. If the actual path is lesser than the wildcard path, add necessary 
                 * parameters to it to complete it.
                 */
                foreach ($fieldParams as $fieldIndex => $fieldParam) {
                    $actualPathParams[$fieldIndex] ??= 0;

                    if ($fieldParam === '*' || $fieldParam === $actualPathParams[$fieldIndex]) {
                        $resolvedPath .= "{$actualPathParams[$fieldIndex]}.";
                        $resolvedPathIsEmpty = false;
                        continue;
                    }

                    if ($resolvedPathIsEmpty) {
                        continue 2;
                    }

                    /**
                     * If the actual path parameters array is shorter than wildcard 
                     * parameters path, fill up the path with the remaining 
                     * parameters to complete it.
                     */
                    $remainingParts = \implode('.', \array_slice($fieldParams, $fieldIndex));
                    $remainingParts = \str_replace(['*.', '.*.', '.*'], ['0.', '.0.', '.0'], $remainingParts);

                    $resolvedPath .= "{$remainingParts}";
                    $resolvedPathIsEmpty = false;
                    break;
                }

                // Remove any unnecessary characters from the resolved path string.
                $resolvedPath = \rtrim($resolvedPath, '.');

                // Collect the custom error meessages bound to the validation rules of the given input array path if any.
                $customErrors = [];

                foreach ($rules as $rule) {
                    if (\is_string($rule) && isset($this->messages["{$field}.{$rule}"])) {
                        $customErrors["{$resolvedPath}.{$rule}"] = $this->messages["{$field}.{$rule}"];
                    }
                }

                // Validate the value of the resolved input path.
                $this->evaluateFieldRules($resolvedPath, $rules, $customErrors);
            }
        }

        $this->validated = true;
        return $this;
    }

    /**
     * Evaluate the validation rules for the individual
     *
     * @param   string  $field
     * @param   array   $rules
     * @param   array   $customErrors
     * 
     * @return  void
     */
    protected function evaluateFieldRules(string $field, array $rules, array $customErrors = []): void
    {
        foreach ($rules as $index => $rule) {
            $ruleDataType = \gettype($rule);

            $evaluation = match ($ruleDataType) {
                'string'    =>  $this->evaluateRuleFromString($rule, $field, $index),
                'object'    =>  $this->evaluateRuleFromObject($rule, $field, $index),
                default     =>  throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid rule at the index [{$index}].")
            };

            // If the validation succeeds.
            if ($evaluation['result'] === true) {
                continue;
            }

            // Prepare the validation error message.
            $ruleName   =   $ruleDataType === 'object' ? Rule::keyOf($rule) : $rule;
            $message    =   $customErrors["{$field}.{$ruleName}"] ?? $this->messages["{$field}.{$ruleName}"] ?? $evaluation['instance']->message();
            $message    =   \str_replace([':{field}', '\:{field}'], [$field, ':{field}'], $message);

            // Add the validation error message.
            $this->errors->add($field, $message);

            // // If the validator is set to stop on the first failure.
            if ($this->stopOnFail) {
                break;
            }
        }
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
            'result'    =>  $instance->setInput($this->input)->setField($field)->check($this->input->get($field)),
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
                'result'    =>  $rule->setInput($this->input)->setField($field)->check($this->input->get($field)),
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
            'result'    =>  $rule->setInput($this->input)->setField($field)->check($this->input->get($field)),
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
