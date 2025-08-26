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
        // Prepare the validation rules before performing the actual validation.
        $this->rules    =   $this->transformRules($this->rules);
        $this->rules    =   $this->resolveWildCardParameters($this->rules);
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
        foreach ($givenRules as $field => $rules) {
            if (\is_string($rules)) {
                $rules = \explode('|', $rules);
            }

            if (!\is_array($rules)) {
                throw new Exception("[Developer][Exception]: The field [{$field}] must have validation rules specified either as a [STRING] or [INDEXED ARRAY].");
            }

            $givenRules[$field] = $rules;
        }

        return $givenRules;
    }

    /**
     * Transform the the array path containing any wildcard parameter(s) to its actual representable paths from the input array.
     *
     * @param array<int|string, mixed> $givenRules
     * 
     * @return array<string, mixed> $givenRules
     */
    protected function resolveWildCardParameters(array $givenRules): array
    {
        // Obtain all of the array paths in dot notation format from the input array.
        $paths = $this->input->paths();

        // Iterate through the given rules to find out & process the paths containing the wildcard parameters.
        foreach ($givenRules as $field => $rules) {
            $field = (string) $field;

            // If the path does not contain any wildcard parameter(s), skip the current iteration. 
            if (\preg_match('/(\*|\.\*)/', $field) === 0) {
                continue;
            }

            $fieldParams    =   \explode('.', $field);
            $matchedPaths   =   [];

            // Iterate through each of the available input paths.
            foreach ($paths as $path) {
                $pathsegments   =   \explode('.', $path);
                $resolvedPath   =   '';

                // Compare each segment of the rule key against the input path.
                foreach ($fieldParams as $fieldIndex => $fieldParam) {
                    $pathsegments[$fieldIndex]  ??= 0;

                    // If the rule segment is a wildcard '*', get the corresponding path segment from the input.
                    if ($fieldParam === '*') {
                        $resolvedPath .= "{$pathsegments[$fieldIndex]}.";
                        continue;
                    }

                    // If the rule segment is an escaped wildcard or dot, remove the escape character.
                    if (\in_array($fieldParam, ['\*', '\.'])) {
                        $fieldParam = \str_replace('\\', '', $fieldParam);
                    }

                    // If the rule segment does not exist in the input path,
                    // or if it doesn't match, resolve the rest of the path based on the rule.
                    if (!isset($pathsegments[$fieldIndex]) || $fieldParam !== $pathsegments[$fieldIndex]) {
                        // Get the remaining segments from the original rule.
                        $remainingSegments = \array_slice($fieldParams, $fieldIndex);

                        // Build the rest of the path from the remaining rule segments.
                        foreach ($remainingSegments as $remainingRuleSegment) {
                            if ($remainingRuleSegment === '*') {
                                $resolvedPath .= "0.";
                                continue;
                            }

                            if (\in_array($remainingRuleSegment, ['\*', '\.'])) {
                                $remainingRuleSegment = \str_replace('\\', '', $remainingRuleSegment);
                            }

                            $resolvedPath .= "{$remainingRuleSegment}.";
                        }

                        // Exit the inner loop since a mismatch was found.
                        break;
                    }

                    // If the segments match, add the current path segment to the resolved path.
                    $resolvedPath .= "{$pathsegments[$fieldIndex]}.";
                }

                // Store the final resolved path after removing any trailing dots.
                $matchedPaths[] = \rtrim($resolvedPath, '.');
            }

            // Merge the newly matched paths with the existing rules and remove the original wildcard rule.
            $givenRules = \array_merge($givenRules, \array_fill_keys(\array_unique($matchedPaths), $rules));
            unset($givenRules[$field]);
        }

        return $givenRules;
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
                $evaluation = match (\gettype($rule)) {
                    'string'    =>  $this->evaluateRuleFromString($rule, $field, $index),
                    'object'    =>  $this->evaluateRuleFromObject($rule, $field, $index),
                    default     =>  throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid rule at the index [{$index}].")
                };

                // If the validation was successful, there is no need to proceed further below.
                if ($evaluation['result'] === true) {
                    continue;
                }

                // Make necessary transformations to the error message.
                $error = \str_replace(':{field}', $field, $evaluation['rule']->message());

                $this->errors->add($field, $error);

                if ($this->stopOnFail) {
                    break 2;
                }
            }
        }

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
        $rule       =   \explode(':', $rule);
        $rule[0]    =   Rule::valueOf($rule[0]);

        if (\is_null($rule[0])) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$rule[0]}] at the index [{$index}].");
        }

        /**
         * The validation will be aborted if both these conditions are true:
         *  [1] The input is equal to NULL.
         *                  and
         *  [2] The rule to be evaluated is not set to be run mandatorily.
         */
        if ($this->input->isNull($field) && !\in_array(MandatoryRuleInterface::class, \class_implements($rule[0]))) {
            return ['result' => true];
        }

        // Prepare the arguments that'll be passed to the rule constructor.
        $arguments = isset($rule[1]) ? \preg_split('/(?<!\\\\),/', $rule[1]) : [];
        $arguments = \array_map(fn($arg) => \str_replace('\\,', ',', $arg), $arguments);

        // Instantiate the rule class to perform the validation.
        $instance = new $rule[0](...$arguments);

        // Perform the actual validation & return its result.
        return [
            'result'    =>  $instance->setInput($this->input)->check($field, $this->input->get($field)),
            'rule'      =>  $instance
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
                'rule'      =>  $rule
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
        if ($this->input->notNull($field) || $rule instanceof MandatoryRuleInterface) {
            return [
                'result'    =>  $rule->setInput($this->input)->check($field, $this->input->get($field)),
                'rule'      =>  $rule
            ];
        }

        return ['result' => true];
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
