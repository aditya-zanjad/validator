<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use Exception;
use AdityaZanjad\Validator\Enums\Rule;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Interfaces\MandatoryRule;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

class Validator
{
    protected Error $errors;

    protected bool $stopOnFail = false;

    protected bool $alreadyValidated = false;

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
        $this->errors ??= new Error();

        if ($this->alreadyValidated) {
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
                    $ruleInstance = $this->makeRuleInstanceFromRuleName($rule);
                }

                if (!$ruleInstance instanceof ValidationRule) {
                    throw new Exception("[Developer][Exception]: The field [{$field}] has an invalid validation rule at the index [{$index}].");
                }

                if (!$ruleInstance instanceof MandatoryRule && $this->input->isMissingOrNull($field)) {
                    continue;
                }

                $validationSuccessful = $ruleInstance
                    ->setFieldName($field)
                    ->setInputInstance($this->input)
                    ->validate($this->input->get($field));

                if ($validationSuccessful) {
                    continue;
                }

                $error  =   $ruleInstance->error();
                $error  =   \str_replace(':{field}', $field, $error);

                $this->errors->add($field, $error);
    
                if ($this->stopOnFail === true) {
                    break 2;
                }
            }
        }

        $this->alreadyValidated = true;
    }

    protected function makeRuleInstanceFromRuleName(string $rule)
    {
        $rule       =   \explode(':', $rule);
        $ruleClass  =   Rule::valueOf($rule[0]);

        if (\is_null($ruleClass)) {
            throw new Exception("[Developer][Exception]: The validation rule [{$rule[0]}] either does not exist OR is invalid.");
        }

        $ruleParams = isset($rule[1]) ? \explode(',', $rule[1]) : [];
        return new $ruleClass(...$ruleParams);
    }

    public function errors(): Error
    {
        return $this->errors;
    }

    public function passed(): bool
    {
        if (!$this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: You must perform the validation before calling this method.");
        }

        return $this->errors->isEmpty();
    }

    public function failed(): bool
    {
        if (!$this->alreadyValidated) {
            throw new Exception("[Developer][Exception]: You must perform the validation before calling this method.");
        }

        return !$this->errors->isEmpty();
    }
}
