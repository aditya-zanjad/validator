<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

class Validator
{
    protected Error $errors;

    protected bool $stopOnFail = false;

    protected bool $validationAlreadyPerformed = false;

    public function __construct(protected Input $input, protected array $rules)
    {
        //
    }

    protected function stopOnFirstFailure(bool $decision = true): static
    {
        $this->stopOnFail = $decision;
        return $this;
    }

    public function validate(): void
    {
        if ($this->validationAlreadyPerformed) {
            throw new Exception("[Developer][Exception]: The validation has already been performed for this instance. Instead, create a new instance to perform the new validation.");
        }

        foreach ($this->rules as $field => $rules) {
            $field = (string) $field;

            if (\is_string($rules)) {
                $rules = \explode('|', $rules);
            }

            if (!\is_array($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be specified either in a STRING or an ARRAY format.");
            }

            if (!\array_is_list($rules)) {
                throw new Exception("[Developer][Exception]: The validation rules for the field [{$field}] must be in the form of an INDEXED ARRAY.");
            }

            if (\in_array('nullable', $rules) && $this->input->isMissingOrNull($field)) {
                continue;
            }

            foreach ($rules as $index => $rule) {
                $ruleInstance = null;

                if (\is_callable($rule)) {
                    $ruleInstance = new Callback($rule);
                }

                if (\is_string($rule)) {
                    $ruleInstance = $this->makeRuleFromName($rule);
                }

                if (\is_null($ruleInstance) || !$ruleInstance instanceof ValidationRule) {
                    throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule at the index [{$index}].");
                }

                $validationSucceeded = $ruleInstance
                    ->setFieldName($field)
                    ->setInputInstance($this->input)
                    ->validate($this->input->get($field));

                if ($validationSucceeded) {
                    continue;
                }

                $error = \str_replace(':{field}', $field, $ruleInstance->error());
                $this->errors->add($field, $error);
    
                if ($this->stopOnFail === true) {
                    break 2;
                }
            }
        }
    }

    protected function makeRuleFromName(string $rule)
    {
        $rule       =   \explode(':', $rule);
        $ruleClass  =   Rule::valueOf($rule[0]);

        if (\is_null($ruleClass)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$ruleClass}] does not exist.");
        }

        $ruleParams = isset($rule[1]) ? \explode(',', $rule[1]) : [];
        return new $ruleClass(...$ruleParams);
    }

    protected function errors(): Error
    {
        return $this->errors;
    }

    protected function failed(): bool
    {
        return !$this->errors->isEmpty();
    }
}
