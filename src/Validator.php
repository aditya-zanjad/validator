<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Closure;
use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Interfaces\MandatoryRule;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

class Validator
{
    /**
     * Decide whether or not to stop on the first validation failure.
     *
     * @var bool $mustStopOnFail
     */
    protected bool $mustStopOnFail = false;

    /**
     * To Keep track of if the validation has already been performed or not.
     *
     * @var boolean
     */
    protected bool $alreadyValidated = false;

    /**
     * @param   \AdityaZanjad\Validator\Input                           $input      =>  Contain & manage input data
     * @param   array<int|string, mixed>                                $rules      =>  Validation rules that we want to apply on the given input data
     * @param   array<string, string>                                   $messages   =>  Custom validation error messages to override the default validation messages.
     * @param   \AdityaZanjad\Validator\Error                           $errors     =>  To contain & manage validation errors
     */
    public function __construct(protected Input $input, protected array $rules, protected array $messages = [], protected Error $errors = new Error())
    {
        //
    }

    /**
     * To decide whether or not the validation must be stopped on the first validation failure.
     *
     * @param bool $decision
     * 
     * @return static
     */
    protected function mustStopOnFail(bool $decision = true): static
    {
        $this->mustStopOnFail = $decision;
        return $this;
    }

    /**
     * Perform the data validation.
     *
     * @return void
     */
    public function validate(): void
    {
        if ($this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: Validation Already Done.");
        }

        foreach ($this->rules as $field => $rules) {
            $field  =   (string) $field;
            $rules  =   $this->transformFieldRules($field, $rules);

            if ($this->shouldNotValidateField($field, $rules)) {
                continue;
            }

            foreach ($rules as $index => $rule) {
                $rule = match (\gettype($rule)) {
                    'string'    =>  $this->instantiateRuleFromString($rule, $field, $index),
                    'object'    =>  $this->instantiateRuleFromObject($rule, $field, $index),
                    default     =>  throw new Exception("[Developer][Exception]: Invalid rule at index {$index} for field {$field}")
                };

                if ($this->shouldNotEvaluateRule($field, $rule)) {
                    continue;
                }

                $validationSucceeded = $rule->setFieldName($field)
                    ->setInputInstance($this->input)
                    ->validate($this->input->get($field));

                if ($validationSucceeded) {
                    continue;
                }

                $this->errors->add($field, \str_replace(':{field}', $field, $rule->error()));
    
                if ($this->mustStopOnFail() === true) {
                    break 2;
                }
            }
        }

        $this->alreadyValidated = true;
    }

    protected function transformFieldRules(string $field, string|array $rules): array
    {
        if (\is_string($rules)) {
            $rules = \explode('|', $rules);
        }

        if (!\is_array($rules)) {
            throw new Exception("[Developer][Exception]: The validation rules for the field {$field} must be specified either in STRING/ARRAY format.");
        }

        if (!\array_is_list($rules)) {
            throw new Exception("[Developer][Exception]: The validation rules for the field {$field} must be in the form of an INDEXED ARRAY.");
        }

        return $rules;
    }

    protected function shouldNotValidateField(string $field, array $rules): bool
    {
        return \in_array('nullable', $rules) && $this->input->isMissingOrNull($field);
    }

    protected function shouldNotEvaluateRule(string $field, ValidationRule $rule): bool
    {
        return !$rule instanceof MandatoryRule && $this->input->isMissingOrNull($field);
    }

    protected function instantiateRuleFromString(string $rule, string $field, int|string $index)
    {
        $rule       =   \explode(':', $rule);
        $ruleClass  =   Rule::valueOf($rule[0]);

        if (\is_null($ruleClass)) {
            throw new Exception("[Developer][Exception]: The field {$field} is supplied with an invalid validation rule at the index {$index}");
        }

        $ruleParams = isset($rule[1]) ? \explode(',', $rule[1]) : [];
        return new $ruleClass(...$ruleParams);
    }

    protected function instantiateRuleFromObject(object $rule, string $field, int|string $index)
    {
        if (\is_callable($rule) || $rule instanceof Closure) {
            return new Callback($rule);
        }

        if (!$rule instanceof ValidationRule) {
            throw new Exception("[Developer][Exception]: The field {$field} has an invalid validation rule at the index {$index}");
        }

        return $rule;
    }

    public function errors(): Error
    {
        return $this->errors;
    }

    public function passed(): bool
    {
        if (!$this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: No Validation. No Result.");
        }

        return $this->errors->isEmpty();
    }

    public function failed(): bool
    {
        if (!$this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: No Validation. No Result.");
        }

        return !$this->errors->isEmpty();
    }
}
