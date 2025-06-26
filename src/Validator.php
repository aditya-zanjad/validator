<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Fluents\Error;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

use function AdityaZanjad\Validator\Utils\arr_indexed;
use function AdityaZanjad\Validator\Utils\arr_last;
use function AdityaZanjad\Validator\Utils\str_contains_v2;

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
     * @var bool $shouldStopOnFailure
     */
    protected bool $shouldStopOnFailure;

    /**
     * To check whether the validation has been already performed or not.
     * 
     * @var bool $alreadyValidated
     */
    protected bool $alreadyValidated;

    /**
     * Inject all the necessary parameters to perform the validation.
     *
     * @param   array<int|string, mixed>
     * @param   array<string, string|array<int, string|\AdityaZanjad\Validator\Base\AbstractRule|callable(int|string $field, mixed $value): bool>>
     * @param   array<string, string>
     */
    public function __construct(array $input, array $rules, array $messages = [])
    {
        // Initialize/transform the supplied constructor arguments before utilizing them.
        $this->input    =   new Input($input);
        $this->rules    =   $this->preprocessRules($rules);
        $this->messages =   $messages;

        // Set other necessary data to their default options.
        $this->errors               =   new Error();
        $this->shouldStopOnFailure  =   false;
        $this->alreadyValidated     =   false;
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
    protected function preprocessRules(array $givenRules)
    {
        $processed = [];

        foreach ($givenRules as $field => $rules) {
            if (\is_string($rules)) {
                $processed[$field] = \explode('|', $rules);
                continue;
            }

            if (!\is_array($rules) || !arr_indexed($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field {$field} must be provided either in a [string] OR [Indexed array] format.");
            }

            if (str_contains_v2($field, '*')) {
                $processed = \array_merge($processed, $this->resolveWildCardsPath($field, $rules));
            }
        }

        return $processed;
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
                if (!str_contains_v2($path, '.')) {
                    $result[$path] = \array_merge($pathRules, $rules);
                }
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
     * @throws \Exception
     *
     * @return static
     */
    public function validate(): static
    {
        // Do not allow performing the same validation more than once.
        if ($this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: The validation has already been performed for this instance.");
        }

        foreach ($this->rules as $path => $rules) {
            /**
             * If the current field is empty/NULL and its validation rules include
             * the rule 'nullable', this method will return true indicating that
             * its entire validation can be skipped else it'll return false.
             */
            if (\in_array('nullable', $rules) && $this->input->isNull($path)) {
                continue;
            }

            $path = (string) $path;

            foreach ($rules as $index => $rule) {
                $result = null;

                // Evaluate the validation rule & obtain its result.
                switch (\gettype($rule)) {
                    case 'string':
                        $result = $this->executeRuleFromString($rule, $index, $path, $this->input->get($path));
                        break;

                    case 'object':
                        $result = $this->executeRuleFromInstance($rule, $path, $this->input->get($path));
                        break;

                    default:
                        throw new Exception("[Developer][Exception]: The validation rule must be either a [STRING] or a [CALLABLE] or an instance of [" . AbstractRule::class . "]");
                }

                if (!\is_bool($result) && !\is_string($result)) {
                    throw new Exception("[Developer][Exception]: The validation rule at the index [{$index}] for the field [{$path}] must return either a [boolean] OR a [string] value.");
                }

                if ($result === true) {
                    continue;
                }

                $this->errors->add($path, $this->messages["{$path}.{$rule}"] ?? $result);

                if ($this->shouldStopOnFailure) {
                    break 2;
                }
            }
        }

        $this->alreadyValidated = true;
        return $this;
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
     * @return  bool|string
     */
    protected function executeRuleFromString(string $rule, int $ruleIndex, string $field, $value)
    {
        if (empty($rule)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule}] at the index [{$ruleIndex}] for field [{$field}] must not be empty.");
        }

        // Extract the important data from the given parameters to execute the validation rule.
        $rule           =   \explode(':', $rule);
        $ruleName       =   $rule[0];
        $ruleClassName  =   Rule::valueOf($ruleName);

        if (\is_null($ruleClassName)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule [{$ruleName}] at the index [{$ruleIndex}].");
        }

        // Check whether or not the current validation rule should be evaluated.
        if (!$this->shouldExecuteRule($ruleClassName, $field)) {
            return true;
        }

        // Extract rule constructor arguments into the parsable format
        $ruleParams = isset($rule[1])
            ? $this->splitStringifiedArguments($rule[1])
            : [];

        return (new $ruleClassName(...$ruleParams))->setInput($this->input)->check($field, $value);
    }

    /**
     * Split the comma-separated string arguments into an array of arguments.
     *
     * @param string $args
     * 
     * @return array<int, string>
     */
    protected function splitStringifiedArguments(string $args)
    {
        // Split the arguments using regex.
        if (function_exists('\\preg_split')) {
            return array_map(function ($arg) {
                return \str_replace(['\\', '\\\\'], [',', '\\'], $arg);
            }, \preg_split('/(?<!\\\\),/', $args));
        }

        $result     =   [];
        $buffer     =   '';
        $length     =   strlen($args);
        $escaped    =   false;

        for ($i = 0; $i < $length; $i++) {
            $char = $args[$i];

            if ($escaped) {
                $buffer     .=  $char;
                $escaped    =   false;

                continue;
            }

            switch ($char) {
                case '\\':
                    $escaped = true;
                    continue;

                case ',':
                    $result[]   =   $buffer;
                    $buffer     =   '';
                    continue;

                default:
                    $buffer .= $char;
                    break;
            }
        }

        $result[] = $buffer;

        foreach ($result as &$part) {
            $part = str_replace(['\\,', '\\\\'], [',', '\\'], $part);
        }

        return $result;
    }

    /**
     * Evaluate the instance rule & return its result.
     *
     * @param   \AdityaZanjad\Validator\Abstracts\AbstractRule|callable(string $field, mixed $value): bool|string   $rule       =>  The validation rule that we need to evaluate.
     * @param   string                                                                                              $field      =>  The dot notation path towards the input field.
     * @param   mixed                                                                                               $value      =>  Value of the given field.
     *
     * @throws  \Exception
     *
     * @return  bool|string
     */
    protected function executeRuleFromInstance($rule, string $field, $value)
    {
        if (!$this->shouldExecuteRule($rule, $field)) {
            return null;
        }

        if ($rule instanceof AbstractRule) {
            return $rule->setInput($this->input)->check($field, $value);
        }

        return $rule($field, $value, $this->input);
    }

    /**
     * Check if the validation rule should be evaluated or not.
     *
     * The validation rule should be evaluated based on the following conditions:
     * [1] The validation rule is set to run mandatorily regardless of whether the input field is present or not.
     * [2] The given input field is not equal to NULL.
     *
     * @param   string|\AdityaZanjad\Validator\Abstracts\AbstractRule|callable(string $field, mixed $value): bool|string    $rule
     * @param   string                                                                                                      $field
     *
     * @return  bool
     */
    protected function shouldExecuteRule($rule, string $field): bool
    {
        $inputIsPresent = $this->input->notNull($field);

        if ($inputIsPresent) {
            return true;
        }

        if (\is_callable($rule) && $inputIsPresent) {
            return true;
        }

        if (\in_array(RequisiteRule::class, \class_implements($rule))) {
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
     * @return \AdityaZanjad\Validator\Fluents\Error
     */
    public function errors(): Error
    {
        return $this->errors;
    }
}
