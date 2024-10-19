<?php

namespace AdityaZanjad\Validator;

use Exception;
use InvalidArgumentException;
use AdityaZanjad\Validator\Rules\Rule;
use AdityaZanjad\Validator\Enums\ValidStringifiedRule;
use AdityaZanjad\Validator\Rules\Constraints\RequiredIf;
use AdityaZanjad\Validator\Interfaces\RequiredConstraint;

use function AdityaZanjad\Validator\Utils\{str_after};

/**
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 */
class Validator
{
    /**
     * Decide whether or not to stop on the first validation failure.
     *
     * Setting this option to true will stop the validation process immediately on the
     * first validation failure.
     *
     * @var bool $stopOnFirstFailure
     */
    protected bool $stopOnFirstFailure = false;

    /**
     * Inject necessary data into the class.
     *
     * @param   \AdityaZanjad\Validator\Input   $data
     * @param   array<string, string|array>     $rules
     * @param   array<string, string>           $messages
     * @param   \AdityaZanjad\Validator\Error   $errors
     *
     * @throws  \InvalidArgumentException
     */
    public function __construct(protected Input $data, protected array $rules, protected array $messages, protected Error $errors)
    {
        if (empty($this->rules)) {
            throw new InvalidArgumentException("[Developer][Exception]: The parameter [rules] is empty. How am I supposed to validate the parameter [data].");
        }

        $this->data     =   $this->data;
        $this->messages =   $messages;
    }

    /**
     * Executing this method will cause the validation process to stop
     *
     * @return static
     */
    public function stopOnFirstFailure(): static
    {
        if ($this->stopOnFirstFailure) {
            throw new Exception("[Developer][Exception]: The validator is is already set to stop on the first validation failure.");
        }

        $this->stopOnFirstFailure = true;
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
            // Convert the string of rules to an array of rules. For example, 'required|string|min:1' to '['required', 'string', 'min:1']'
            if (is_string($rules)) {
                $rules = explode('|', $rules);
            }

            // The validation rules specified for each field must ultimately end up in the array format.
            if (!is_array($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be specified either in a [STRING] or [ARRAY] format.");
            }

            if (empty($rules)) {
                throw new Exception("[Developer][Exception]: There are no validation rules specified for the field [{$field}].");
            }

            // Get value of the field being currently validated.
            $value          =   $this->data->get($field);
            $valueIsNotSet  =   is_null($value);

            // Validate the given array path value against the given set of validation rules.
            foreach ($rules as $rule) {
                // If the input field NULL OR not given & the current rule is not 'RequiredConstraint' one, then skip its execution.
                $shouldSkipThisRule = $valueIsNotSet 
                    && !$rule instanceof RequiredConstraint 
                    && is_string($rule) 
                    && !empty($rule) 
                    && !str_contains($rule, 'required');

                if ($shouldSkipThisRule) {
                    continue;
                }

                $result = match (gettype($rule)) {
                    'string'    =>  $this->evaluateStringifiedRule($rule, $field, $value),
                    'object'    =>  $rule instanceof Rule ? $rule->setInputInstance($this->data)->check($field, $value) : call_user_func($rule, $field, $value),
                    default     =>  throw new Exception("[Developer][Exception]: The validation rules must be specified in either [STRING] OR [" . Rule::class . "] OR [callable] formats.")
                };

                if ($result !== true) {
                    $this->errors->add($field, $result);
                }

                if ($this->stopOnFirstFailure) {
                    break 2;
                }
            }
        }

        return $this;
    }

    /**
     * TODO => Complete this functionality in the next commit.
     *
     * Expand the given wildcard path to the actual corresponding array path(s).
     *
     * @return void
     */
    protected function resolveWildCardPaths(): void
    {
        /**
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!! TODO !!!!!!!!!!!!!!!!!!!!!!!
         *  Resolve the wildcard paths to their actual input array
         * paths & apply their validation rules to these paths.
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!! TODO !!!!!!!!!!!!!!!!!!!!!!!
         */
        // $matchedPaths = preg_grep("/[$path]/", $this->paths);

        // // If any of the array paths matches with the 'wild card path' pattern.
        // if (is_array($matchedPaths)) {
        //     $matchedPaths   =   array_fill_keys($matchedPaths, $this->rules[$path]);
        //     $this->rules    =   array_merge($this->rules, $matchedPaths);

        //     unset($this->rules[$path]);
        //     return;
        // }

        // unset($this->rules[$path]);
    }

    /**
     * Validate the array field against the given stringified rule.
     *
     * @param   string  $rule   =>  Name of the validation rule.
     * @param   string  $field  =>  The dot notation path to the field inside the array.
     * @param   mixed   $value  =>  The value of the field being applied the validation rule.
     *
     * @return  bool|string
     */
    protected function evaluateStringifiedRule(string $rule, string $field, mixed $value): bool|string
    {
        $rule       =   explode(':', $rule);
        $rule[0]    =   ValidStringifiedRule::tryFromName($rule[0]);

        if (is_null($rule[0])) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has validation rules which are either invalid OR do not exist.");
        }

        // Extract the rule arguments if they are provided.
        $rule[1] = isset($rule[1]) ? explode(',', str_after($rule[1], ':')) : [];

        $instance = match ($rule[0]) {
            RequiredIf::class   =>  $this->makeInstanceForRequiredIfRule($rule[1]),
            default             =>  new $rule[0](...$rule[1]),
        };

        return $instance->setInputInstance($this->data)->check($field, $value);
    }

    /**
     * Make the object for the rule 'RequiredIf'
     *
     * @param   string                  $ruleClass
     * @param   array<string, string>   $ruleParams
     *
     * @throws  \Exception
     *
     * @return  \AdityaZanjad\Validator\Rules\Constraints\RequiredIf
     */
    protected function makeInstanceForRequiredIfRule(array $ruleParams): RequiredIf
    {
        if (count($ruleParams) < 2) {
            throw new Exception('[Developer][Exception]: The rule [required_if] requires at least two parameters.');
        }

        return new RequiredIf($ruleParams);
    }

    /**
     * Check if the validation has succeeded or failed.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return !$this->errors->isEmpty();
    }

    /**
     * Get an instance to manage the validation errors.
     *
     * @return \AdityaZanjad\Validator\Error
     */
    public function errors(): Error
    {
        return $this->errors;
    }
}
