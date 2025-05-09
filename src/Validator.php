<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Managers\ErrorsManager;
use AdityaZanjad\Validator\Managers\InputsManager;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

use function AdityaZanjad\Validator\Utils\arr_indexed;

/**
 * @version 1.0
 */
class Validator
{
    /**
     * To hold & manage all of the input data that we want to validate.
     *
     * @var \AdityaZanjad\Validator\Managers\InputsManager $input
     */
    protected InputsManager $input;

    /**
     * To hold the validation rules that we want to apply against the given input data.
     *
     * @var array<string, string|array<int, string|callable|>> $rules
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
     * @var \AdityaZanjad\Validator\Managers\ErrorsManager $errors
     */
    protected ErrorsManager $errors;

    /**
     * Useful in deciding whether or not to stop the validator on first failure.
     *
     * @var bool $shouldStopOnFailure
     */
    protected bool $shouldStopOnFailure;

    /**
     * Inject all the necessary parameters to perform the validation.
     *
     * @param   \AdityaZanjad\Validator\Managers\InputsManager
     * @param   \AdityaZanjad\Validator\Managers\ErrorsManager
     * @param   array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     * @param   array<string, string>
     */
    public function __construct(InputsManager $input, ErrorsManager $error, array $rules, array $messages = [])
    {
        $this->input                =   $input;
        $this->errors               =   $error;
        $this->rules                =   $rules;
        $this->messages             =   $messages;
        $this->shouldStopOnFailure  =   false;
    }

    /**
     * Stop the validation process immediately on the first validation failure.
     *
     * @param bool $shouldStop
     *
     * @return \AdityaZanjad\Validator\Validator
     */
    public function stopOnFirstFailure(bool $shouldStop = true)
    {
        $this->shouldStopOnFailure = $shouldStop;
        return $this;
    }

    /**
     * Perform the validation process.
     *
     * @return static
     */
    public function validate(): static
    {
        foreach ($this->rules as $path => $rules) {
            $path   =   (string) $path;
            $rules  =   $this->transformRules($path, $rules);

            foreach ($rules as $field => $fieldRules) {
                /**
                 * If the current field is empty/NULL and its validation rules include
                 * the rule 'nullable', this method will return true indicating that
                 * its entire validation can be skipped else it'll return false.
                 */
                if (in_array('nullable', $rules) && !$this->input->exists($path)) {
                    continue;
                }

                $field = (string) $field;

                foreach ($fieldRules as $index => $rule) {
                    $result = null;

                    // Evaluate the validation rule & obtain its result.
                    switch (gettype($rule)) {
                        case 'string':
                            $result = $this->evaluateStringifiedRule($rule, $index, $field, $this->input->get($field));
                            break;

                        case 'object':
                            $result = $this->evaluateInstanceRule($rule, $index, $field, $this->input->get($field));
                            break;

                        default:
                            throw new Exception("[Developer][Exception]: The validation rule must be either a [STRING] or a [CALLABLE] or an instance of [" . AbstractRule::class . "]");
                    }

                    // If the validation is successful.
                    if ($result === true || is_null($result)) {
                        continue;
                    }

                    if ($result !== false && !is_string($result)) {
                        throw new Exception("[Developer][Exception]: The validation rule for the field [$field] at the index [{$index}] should evaluate to either a [boolean] OR [string] value.");
                    }

                    $this->errors->add($field, $result);

                    if ($this->shouldStopOnFailure) {
                        break 3;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Expand the given wildcard parameters path to its actual input data paths.
     *
     * @param   string              $path
     * @param   array<int, mixed>   $rules
     *
     * @return  array<int, string>  $actualPaths
     */
    protected function resolveWildCards(string $path, array $rules): array
    {
        if ($path === '*') {
            return array_fill_keys(array_keys($this->input->all()), $rules);
        }

        // Get all the paths that'll match with the given wildcard path either
        // completely or partially from left-to-right.
        $actualPaths = preg_grep("#^{$path}$#", $this->input->keys());

        // If no matches found, we want to create at least one dummy path to
        // perform validation if necessary.
        if (empty($actualPath)) {
            return [str_replace($path, '*', '0') => $rules];
        }

        $wildCardParams         =   explode('.', $path);
        $wildCardParamsLength   =   count($wildCardParams);

        // Loop through the array of found paths & complete any path which is
        // incomplete when compared to the wildcard path.
        foreach ($actualPaths as $index => $actualPath) {
            $actualParams       =   explode('.', $actualPath);
            $actualParamsLength =   count($actualParams);

            // If the current path perfectly matches with the wildcard pattern, there
            // is no need to modify anything.
            if ($wildCardParamsLength === $actualParamsLength) {
                continue;
            }

            // If the number of parameters in the wildcard path exceed the number of
            // parameters in the current actual path, we need to add the missing
            // parameters to the current path.
            if ($actualParamsLength < $wildCardParamsLength) {
                $actualParams           =   array_replace($wildCardParams, $actualParams);
                $actualParams           =   array_map(fn ($param) => $param === '*' ? '0' : $param, $actualParams);
                $actualParams           =   implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }

            // If number of parameters in the current path exceed the number of parameters
            // in the wildcard parameter path, we need to trim down current path
            // containing actual parameters.
            if ($actualParamsLength > $wildCardParamsLength) {
                $actualParams           =   array_slice($actualParams, 0, $wildCardParamsLength);
                $actualParams           =   implode('.', $actualParams);
                $actualPaths[$index]    =   $actualParams;

                continue;
            }
        }

        return array_fill_keys($actualPaths, $rules);
    }

    /**
     * Make necessary changes to the validation rules before actually evaluating them.
     *
     * The changes include:
     * [1] Transforming the string of rules into the array of rules.
     * [2] Evaluating the wildcard input field path to its actual corresponding input field paths.
     * [3] After performing the above steps, collecting the input field path(s) & their validation rule(s) into an array.
     *
     * @param   string          $field
     * @param   string|array    $rules
     *
     * @throws  \Exception
     *
     * @return  array<int|string, string|callable|\AdityaZanjad\ValidationRule>
     */
    protected function transformRules(string $field, string|array $rules): array
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        if (!arr_indexed($rules)) {
            throw new Exception("[Developer][Exception]: The validation rules for the field {$field} must be provided in either a [STRING] OR [Indexed ARRAY] format.");
        }

        if (str_contains($field, '*')) {
            return $this->resolveWildCards($field, $rules);
        }

        return [$field => $rules];
    }

    /**
     * Evaluate the rule provided in the string format.
     *
     * @param   string  $rule
     * @param   string  $field
     * @param   mixed   $value
     *
     * @throws  \Exception
     *
     * @return  null|bool|string
     */
    protected function evaluateStringifiedRule(string $rule, int $ruleIndex, string $field, $value)
    {
        if (empty($rule)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule}] at the index [{$ruleIndex}] for field [{$field}] must not be empty.");
        }

        // Extract the important data from the given parameters to execute the validation rule.
        $rule           =   explode(':', $rule);
        $ruleName       =   $rule[0];
        $ruleClassName  =   Rule::valueOf($ruleName);

        if (is_null($ruleClassName)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$ruleName}] at the index [{$ruleIndex}].");
        }

        // Check whether or not the current validation rule should be evaluated.
        if (!$this->shouldExecuteValidationRule($ruleClassName, $field)) {
            return null;
        }

        // Instantiate & execute the validation rule.
        $ruleParams     =   isset($rule[1]) ? explode(',', $rule[1]) : [];
        $ruleInstance   =   new $ruleClassName(...$ruleParams);

        return $ruleInstance->setInput($this->input)->check($field, $value);
    }

    /**
     * Evaluate the instance rule & return its result.
     *
     * @param   callable|\AdityaZanjad\Validator\Abstracts\AbstractRule $rule       =>  The validation rule that we need to evaluate.
     * @param   int                                                     $ruleIndex  =>  The index of the rule in the given field's rules array.
     * @param   string                                                  $field      =>  The dot notation path towards the input field.
     * @param   mixed                                                   $value      =>  Value of the given field.
     *
     * @throws  \Exception
     *
     * @return  bool|string
     */
    protected function evaluateInstanceRule(callable|AbstractRule $rule, int $ruleIndex, string $field, $value)
    {
        if (!$this->shouldExecuteValidationRule($rule, $field)) {
            return null;
        }

        if ($rule instanceof AbstractRule) {
            return $rule->setInput($this->input)->check($field, $value);
        }

        $result = $rule($field, $value, $this->input);

        // The result returned by the callable must always be in either a [STRING] OR [BOOLEAN] format.
        if (!is_bool($result) || !is_string($result)) {
            throw new Exception("[Developer][Exception]: [{$field}][{$ruleIndex}] The callable validation rule must return either a [boolean] OR [string] value.");
        }

        return $result;
    }

    /**
     * Check if the validation rule should be evaluated or not.
     *
     * The validation rule should be evaluated based on the following conditions:
     * [1] The validation rule is set to run mandatorily regardless of whether the input field is present or not.
     * [2] The given input field is not equal to NULL.
     *
     * @param   string|\AdityaZanjad\Validator\Abstracts\AbstractRule   $rule
     * @param   string                                                  $field
     *
     * @return  bool
     */
    protected function shouldExecuteValidationRule(string|AbstractRule $rule, string $field): bool
    {
        $implementedInterfaces = class_implements($rule);

        if (in_array(RequisiteRule::class, $implementedInterfaces)) {
            return true;
        }

        if ($this->input->notNull($field)) {
            return true;
        }

        return false;
    }

    /**
     * Check whether the validation was successful or not.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Get an instance of the class that holds & manages the error messages.
     *
     * @return \AdityaZanjad\Validator\Managers\ErrorsManager
     */
    public function errors(): ErrorsManager
    {
        return $this->errors;
    }
}
