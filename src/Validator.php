<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Fluents\Error;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class Validator
{
    /**
     * To hold & manage all of the input data that we want to validate.
     *
     * @var \AdityaZanjad\Validator\Fluents\Input $input
     */
    protected Input $input;

    /**
     * To hold the validation rules that we want to apply against the given input data.
     *
     * @var array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     */
    protected array $rules;

    /**
     * To hold the custom validation error messages for the specified validation rules.
     *
     * @var array<string, string> $messages
     */
    protected array $messages;

    /**
     * To hold & manage the validation errors.
     *
     * @var \AdityaZanjad\Validator\Fluents\Error $errors
     */
    protected Error $errors;

    /**
     * Useful in deciding whether or not to stop the validator on first failure.
     *
     * @var bool $abortOnFail
     */
    protected bool $abortOnFail;

    /**
     * To check whether the validation has been already performed or not.
     *
     * @var bool $validated
     */
    protected bool $validated;

    /**
     * Inject all the necessary parameters to perform the validation.
     *
     * @param   array<int|string, mixed>
     * @param   array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     * @param   array<string, string>
     */
    public function __construct(array $input, array $rules, array $messages = [])
    {
        // Initialize & Transform the necessary data required to perform the actual validation.
        $this->input        =   new Input($input);
        $this->rules        =   $this->transformRules($rules);
        $this->messages     =   $messages;
        $this->errors       =   new Error();
        $this->abortOnFail  =   false;
        $this->validated    =   false;
    }

    /**
     * Reorganize/Preprocess the given validation rules before they are actually evaluated.
     *
     * @param array<string, string|array<int|string, string|AbstractRule|callable(string $field, mixed $value): bool>>
     *
     * @return array<string, string|array<int|string, string|AbstractRule|callable(string $field, mixed $value): bool>>
     */
    protected function transformRules(array $givenRules): array
    {
        foreach ($givenRules as $field => $rules) {
            // If the rules are given in the form of a string, convert them into
            if (\is_string($rules)) {
                $rules = \explode('|', $rules);
            }

            if (!\is_array($rules)) {
                throw new Exception("[Developer][Exception]: The field [{$field}] must have validation rules specified either as a [STRING] or [INDEXED ARRAY].");
            }

            $givenRules[$field] = $rules;

            /**
             * If the current field contains wildcard paths, we need to find out 
             * the actual paths corresponding to this and then add those paths 
             * to the rules array.
             */
            if (\preg_match('/(\*|\.\*)/', (string) $field) > 0) {
                $givenRules += $this->resolveWildCardsPath($field, $givenRules);
                unset($givenRules[$field]);
            }
        }

        return $givenRules;
    }

    /**
     * Expand the given wildcard parameters path to its actual input data paths.
     *
     * @param   string              $path
     * @param   array<int, mixed>   $rules
     *
     * @return  array<int, string>  $actualPaths
     */
    protected function resolveWildCardsPath(string $path, array $rules): array
    {
        if ($path === '*') {
            $result = [];

            foreach ($this->rules as $path => $pathRules) {
                $explodedPath               =   \explode('.', $path);
                $result[$explodedPath[0]]   =   \array_merge($pathRules, $rules);
            }

            return $result;
        }

        // Get all the paths that match with the wildcard path either completely or partially from left-to-right.
        $actualPaths = \preg_grep("#^{$path}$#", $this->input->keys());

        // If no matches found, we want to create at least one dummy path to perform validation if necessary.
        if (empty($actualPaths)) {
            $search         =   ['.*.', '.*', '*.'];
            $replace        =   ['.0.', '.0', '0.'];
            $modifiedPath   =   \str_replace($search, $replace, $path);

            return [$modifiedPath => $rules];
        }

        $wildCardParams         =   \explode('.', $path);
        $wildCardParamsLength   =   \count($wildCardParams);

        // Loop through the array of found paths & complete any path which is
        // incomplete when compared to the wildcard path.
        foreach ($actualPaths as $index => $actualPath) {
            $actualParams       =   \explode('.', $actualPath);
            $actualParamsLength =   \count($actualParams);

            // If the number of parameters in the wildcard path exceed the number of
            // parameters in the current actual path, we need to add the missing
            // parameters to the current path.
            if ($actualParamsLength < $wildCardParamsLength) {
                $actualParams = \array_replace($wildCardParams, $actualParams);

                $actualParams = \array_map(function ($param) {
                    if ($param === '*') {
                        return '0';
                    }

                    return $param;
                }, $actualParams);

                $actualParams           =   \implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }

            // If number of parameters in the current path exceed the number of parameters
            // in the wildcard parameter path, we need to trim down current path
            // containing actual parameters.
            if ($actualParamsLength > $wildCardParamsLength) {
                $actualParams           =   \array_slice($actualParams, 0, $wildCardParamsLength);
                $actualParams           =   \implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }
        }

        return \array_fill_keys($actualPaths, $rules);
    }

    /**
     * Stop the validation process immediately on the first validation failure.
     *
     * @param bool $abortOnFail
     *
     * @return \AdityaZanjad\Validator\Validator
     */
    public function abortOnFail(bool $abortOnFail = true): static
    {
        $this->abortOnFail = $abortOnFail;
        return $this;
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

        foreach ($this->rules as $field => $rules) {
            // Make sure to type cast the array field path to string explicitly as required for the further operations.
            $field = (string) $field;

            // Start evaluating rules for the current field.
            foreach ($rules as $index => $rule) {
                $evaluation = match (gettype($rule)) {
                    'string'    =>  $this->evaluateRuleFromString($rule, $field, $index),
                    'object'    =>  $this->evaluateRuleFromObject($rule, $field, $index),
                    default     =>  throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid rule at the index [{$index}].")
                };

                // If the validation was successful, there is no need to proceed further below.
                if ($evaluation['result'] === true) {
                    continue;
                }

                // Make necessary transformations to the error message.
                $error = $this->messages[$field] ?? $evaluation['rule']->message();
                $error = \str_replace(':{field}', $field, $error);

                $this->errors->add($field, $error);

                if ($this->abortOnFail) {
                    break 2;
                }
            }
        }

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
     * @return  array<string, null|bool|\AdityaZanjad\Validator\Base\AbstractRule>
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
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$ruleClassName}] at the index [{$index}].");
        }

        /**
         * The validation should be performed only when either of these conditions is true:
         *  [1] The input is NOT NULL
         *  [2] The rule is set to be run mandatorily regardless of whether the input is present (NOT NULL) or not (NULL).
         */
        if ($this->input->isNull($field) && !\in_array(RequisiteRule::class, class_implements($ruleClassName))) {
            return ['result' => true];
        }

        // Prepare the rule constructor arguments. Then, create its instance to to perform the validation.
        $arguments  =   isset($rule[1]) ? \preg_split('/(?<!\\\\),/', $rule[1]) : [];
        $arguments  =   \array_map(fn($arg) => \str_replace('\\,', ',', $arg), $arguments);
        $instance   =   new $ruleClassName(...$arguments);

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
        $instance = null;

        /**
         * After much thought or not so much thought I guess, unlike Laravel, I've decided
         * to keep the callback validation rule as 'implicit/requisite'. It means that
         * the callback validation will always be run regardless of whether the
         * input field is present (NOT NULL) or not (NULL).
         */
        if (\is_callable($rule)) {
            $instance = new Callback($rule);
        }

        // The validation rule object should always end as an object of the 'AbstractRule' class no matter what.
        if (!$rule instanceof AbstractRule) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule specified the index [{$index}].");
        }

        /**
         * The validation will be performed only when either of these are 'true':
         *  [1] The input is NOT NULL.
         *  [2] The rule implements the 'RequisiteRule' interface.
         */
        if ($this->input->isNull($field) && !$instance instanceof RequisiteRule) {
            return ['result' => true];
        }

        return [
            'result'    =>  $instance->setInput($this->input)->check($field, $this->input->get($field)),
            'rule'      =>  $instance
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
     * @return \AdityaZanjad\Validator\Fluents\Error
     */
    public function errors(): Error
    {
        return $this->errors;
    }
}
