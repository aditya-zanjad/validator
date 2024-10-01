<?php

namespace AdityaZanjad\Validator;

use Closure;
use Exception;
use InvalidArgumentException;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Interfaces\ConstraintRule;
use AdityaZanjad\Validator\Interfaces\ValidationRule;
use AdityaZanjad\Validator\Exceptions\ValidationFailed;
use AdityaZanjad\Validator\Rules\Constraints\RequiredIf;

use function AdityaZanjad\Validator\Utils\{array_value_exists, array_value_get, str_after};
use function AdityaZanjad\Validator\Utils\{array_value_first, array_to_dot};

/**
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 */
class Validator
{
    /**
     * The array paths to the values from the input data array.
     *
     * @var array<int|string, mixed>
     */
    protected array $paths = [];

    /**
     * To manage the validation errors.
     *
     * @var array<string, array> $errors
     */
    protected array $errors = [];

    /**
     * A list of rules that extend the 'ConstraintRule' interface.
     */
    protected array $constraintRules = [];

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
    public function __construct(protected array $data, protected array $rules, protected array $messages = [])
    {
        if (empty($this->rules)) {
            throw new InvalidArgumentException("[Developer][Exception]: The parameter [rules] is empty. How am I supposed to validate the parameter [data].");
        }

        $this->data             =   $this->data;
        // $this->paths            =   array_keys($this->data);
        $this->messages         =   $messages;
        $this->constraintRules  =   Rule::getConstraintRulesCases();
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
     * @throws \AdityaZanjad\Validator\Exceptions\ValidationFailed
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

            /**
             * The validation rules specified for each field must 
             * ultimately end up in the array format.
             */
            if (!is_array($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be specified either in a [STRING] or [ARRAY] format.");
            }

            if (empty($rules)) {
                throw new Exception("[Developer][Exception]: There are no validation rules specified for the field [{$field}].");
            }

            /**
             * Make some necessary modifications to the rules array.
             *
             * [1] Remove all the duplicated values from the array.
             * [2] Sort rules array so that we can decide whether OR not to skip current field's validation.
             */
            $rules = array_unique($rules);
            usort($rules, fn ($a) => ((is_string($a) && str_contains($a, 'required')) || $a instanceof ConstraintRule) ? -1 : 1);

            // Validate the given array path value against the given set of validation rules.
            foreach ($rules as $rule) {
                if ($this->fieldValidationShouldBeSkipped($field, $rule)) {
                    continue 2;
                }

                $result = match (gettype($rule)) {
                    'string'    =>  $this->evaluateStrRule($field, $rule),
                    'object'    =>  $rule instanceof ValidationRule ? $rule->check($field, $this->data[$field]) : call_user_func($rule, $field, $this->data[$field]),
                    default     =>  throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] should be either [String] / [ValidationRule Instance] / [Callback].")
                };

                // If the validation succeeds skip the current iteration.
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
     * Check if the given field path is constrained to be required.
     *
     * @param   string                                                              $fieldPath
     * @param   string|\Closure|\AdityaZanjad\Validator\Interfaces\ValidationRule   $rules
     *
     * @return  bool
     */
    protected function fieldValidationShouldBeSkipped(string $fieldPath, string|ValidationRule|Closure $rule): bool
    {
        // If the field present in the data & not is NULL, it should be validated.
        if (array_value_exists($this->data, $fieldPath)) {
            return false;
        }

        // If the field is being applied one of the 'required/required_*' rules, it must be validated.
        if (is_string($rule) && str_contains($rule, 'required')) {
            return false;
        }

        // If the rule is an instance of one of the Constrained Rules, the field must be validated.
        if ($rule instanceof ConstraintRule) {
            return false;
        }

        return true;
    }

    /**
     * Validate the array field against the given stringified rule.
     *
     * @param   string  $fieldPath
     * @param   string  $rule
     *
     * @return  bool|string
     */
    protected function evaluateStrRule(string $fieldPath, string $rule): bool|string
    {
        $fieldValue =   array_value_get($this->data, $fieldPath);
        $rule       =   explode(':', $rule);
        $ruleClass  =   Rule::tryFromName($rule[0]);

        if (is_null($ruleClass)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule[0]}] is either invalid OR does not exist.");
        }

        // Extract the parameters that need to passed to the
        // constructor/method of the rule class.
        $ruleParams =   str_after($rule[1] ?? '', ':');
        $ruleParams =   explode(',', $ruleParams);

        // For rules that are not constrained rules. i.e. the rules
        // that are not dependent on the other input fields.
        if (!in_array(ConstraintRule::class, class_implements($ruleClass, true))) {
            $ruleObject = new $ruleClass(...$ruleParams);
            return $ruleObject->check($fieldPath, $fieldValue);
        }

        // For rules, that are constrained to other fields as well
        // along with the fields they are validating.
        [$ruleObject, $constrainedData] = match ($ruleClass) {
            RequiredIf::class => $this->makeRequiredIfRuleObject($ruleClass, $ruleParams),
        };

        $ruleObject->setConstraintData($constrainedData);  // Set the constrained data specific to the rule & perform the validation.
        return $ruleObject->check($fieldPath, $fieldValue);
    }

    /**
     * Make the object for the rule 'RequiredIf'
     *
     * @param   string                  $ruleClass
     * @param   array<string, string>   $ruleParams
     *
     * @throws  \Exception
     *
     * @return  array<string, array<string, mixed>|\AdityaZanjad\Validator\Rules\Constraints\RequiredIf>
     */
    protected function makeRequiredIfRuleObject(string $ruleClass, array $ruleParams): array
    {
        if (count($ruleParams) < 2) {
            throw new Exception('[Developer][Exception]: The rule [required_if] requires at least two parameters.');
        }

        $otherFieldName =   array_splice($ruleParams, 0, 1)[0];
        $ruleObject     =   new $ruleClass(null);

        return [
            $ruleObject,
            [
                'other_field'   =>  $otherFieldName,
                'given_values'  =>  $ruleParams,
                'actual_value'  =>  $this->data[$otherFieldName] ?? null
            ]
        ];
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
}
