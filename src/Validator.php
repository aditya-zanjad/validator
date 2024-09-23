<?php

namespace AdityaZanjad\Validator;

use Exception;
use InvalidArgumentException;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Interfaces\ValidationRule;
use AdityaZanjad\Validator\Exception\ValidationFailed;

use function AdityaZanjad\Validator\Utils\{str_after};
use function AdityaZanjad\Validator\Utils\{array_value_first, array_to_dot};

/**
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 */
class Validator
{
    /**
     * The input data that we want to validate.
     *
     * @var array<int|string, mixed> $data
     */
    protected array $data;

    /**
     * The validation rules that we want to apply against the given input data.
     *
     * @var array<string, string|array> $rules
     */
    protected array $rules;

    /**
     * Custom validation error messages to override the default validation error messages.
     *
     * @var array<string, string>
     */
    protected array $messages;

    /**
     * Paths in dot notation format to each element of the array.
     *
     * @var array<int|string, mixed> $path
     */
    protected array $paths;

    /**
     * To manage the validation errors.
     *
     * @var array<string, array> $errors
     */
    protected array $errors = [];

    /**
     * Decide whether or not to stop on the first validation failure.
     *
     * Setting this option to true will stop the validation process immediately on the
     * first validation failure.
     *
     * @var bool $abortOnFailure
     */
    protected bool $abortOnFailure = false;

    /**
     * Indicates whether an exception should be thrown on the validation failure.
     *
     * @var bool $throwExceptionOnFailure
     */
    protected bool $throwExceptionOnFailure = false;

    /**
     * Inject necessary data into the class.
     *
     * @param   array<int|string, mixed>      $data
     * @param   array<string, string|array>   $rules
     * @param   array<string, string>         $messages
     *
     * @throws  \InvalidArgumentException
     */
    public function __construct(array $data, array $rules, array $messages = [])
    {
        if (empty($rules)) {
            throw new InvalidArgumentException("[Developer][Exception]: The parameter [rules] is empty. How am I supposed to validate the parameter [data].");
        }

        $this->data     =   array_to_dot($data);
        $this->paths    =   array_keys($this->data);
        $this->rules    =   $rules;
        $this->messages =   $messages;
    }

    /**
     * Executing this method will cause the validation process to stop
     *
     * @return static
     */
    public function shouldAbortOnFailure(): static
    {
        if ($this->abortOnFailure) {
            return $this;
        }

        $this->abortOnFailure = true;
        return $this;
    }

    /**
     * Indicates that a validation exception will be thrown on the validation failure.
     *
     * @return static
     */
    public function shouldThrowValidationException(): static
    {
        if ($this->throwExceptionOnFailure) {
            return $this;
        }

        $this->throwExceptionOnFailure = true;
        return $this;
    }

    /**
     * Perform the validation process.
     *
     * @return static
     */
    public function validate(): static
    {
        // Loop through each set of rules.
        foreach ($this->rules as $field => $rules) {
            /**
             * Convert the string of rules to an array of rules.
             * For example, 'required|string|min:1' to '['required', 'string', 'min:1']'
             */
            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }

            if (!is_array($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be specified either in a STRING or ARRAY format.");
            }

            // Validate the value at the given array path against the given set of validation rules.
            foreach ($rules as $rule) {
                $result = match (gettype($rule)) {
                    'string'    =>  $this->evaluateStrRule($rule, $field),
                    'object'    =>  $rule instanceof ValidationRule ? $rule->check($field, $this->data[$field]) : call_user_func($rule, $field, $this->data[$field]),
                    default     =>  throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] should be either [String] / [ValidationRule Instance] / [Callback].")
                };

                if ($result === true) {
                    continue;
                }

                $this->addError($field, $result ?: 'The attribute :{attribute} is invalid');

                if ($this->abortOnFailure) {
                    break 2;
                }

                if ($this->throwExceptionOnFailure) {
                    throw new ValidationFailed($this->firstError(), $this->errors);
                }
            }
        }

        return $this;
    }

    /**
     * Expand the given wildcard path to the actual corresponding array path(s).
     *
     * @param string $path
     *
     * @return void
     */
    protected function resolveWildCardPath(string $path): void
    {
        $matchedPaths = preg_grep("/[$path]/", $this->paths);

        // If any of the array paths matches with the 'wild card path' pattern.
        if (is_array($matchedPaths)) {
            $matchedPaths   =   array_fill_keys($matchedPaths, $this->rules[$path]);
            $this->rules    =   array_merge($this->rules, $matchedPaths);

            unset($this->rules[$path]);
            return;
        }

        unset($this->rules[$path]);
    }

    /**
     * Validate the array field against the given stringified rule.
     *
     * @param   string  $rule
     * @param   string  $field
     *
     * @throws  \Exception
     *
     * @return  bool|string
     */
    protected function evaluateStrRule(string $rule, string $field): bool|string
    {
        $rule       =   explode(':', $rule);
        $ruleClass  =   Rule::tryFromName($rule[0]);

        if (is_null($ruleClass)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule[0]}] is either invalid OR does not exist.");
        }

        $ruleParams = str_after($rule[1] ?? '', ':');
        $ruleParams = explode(',', $ruleParams);

        return (new $ruleClass(...$ruleParams))->check($field, $this->data[$field]);
    }

    /**
     * Check if the validation has succeeded or failed.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Add a new validation error to the errors array.
     *
     * @param   string  $attribute
     * @param   string  $error
     *
     * @return  void
     */
    public function addError(string $attribute, string $error): void
    {
        $this->errors[$attribute][] = str_replace(':{attribute}', $attribute, $error);
    }

    /**
     * Get the first error message of the first field from the errors array.
     *
     * @return mixed
     */
    public function firstError(): mixed
    {
        $firstField = array_value_first($this->errors);

        if (is_null($firstField)) {
            return null;
        }

        return array_value_first($firstField);
    }

    /**
     * Get the first error message for the given field from the errors array.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function firstErrorOf(string $key): mixed
    {
        if (!array_key_exists($key, $this->errors)) {
            return null;
        }

        return array_value_first($this->errors[$key]);
    }

    /**
     * Return all of the validation errors at once as an array.
     *
     * @return array<string, array>
     */
    public function allErrors(): array
    {
        return $this->errors;
    }

    /**
     * Check if any validation error has occurred so far.
     *
     * @return bool
     */
    public function anyError(): bool
    {
        return !empty($this->errors);
    }
}
