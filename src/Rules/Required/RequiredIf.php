<?php

namespace AdityaZanjad\Validator\Rules\Required;

use Closure;
use Exception;
use AdityaZanjad\Validator\Rules\Required\Base\RequiredRule;
use function AdityaZanjad\Validator\Utils\{before as str_before, after as str_after};

/**
 * Check whether the given attribute should be applied the validation rule 'required' or not.
 */
class RequiredIf extends RequiredRule
{
    /**
     * Inject necessary dependency into the class.
     *
     * @param string|Closure $expression
     */
    public function __construct(protected string|Closure $expression)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        return match (gettype($this->expression)) {
            'string'    =>  $this->assertStringifiedExpression($attribute, $value),
            'object'    =>  $this->assetCallbackExpression($attribute, $value),
            default     =>  throw new Exception("[Developer][Exception]: The given expression for applying the validation rule is of invalid type")
        };
    }

    /**
     * Perform validation against the given stringified expression.
     *
     * @param   string  $attribute
     * @param   mixed   $value
     * 
     * @return  bool|string
     */
    protected function assertStringifiedExpression(string $attribute, mixed $value): bool|string
    {
        // Extract the name of the field from the string expression.
        $otherField = str_before($this->expression, ',');

        // Extract the values of this other field.
        $valuesOfOtherField = str_after($this->expression, ',');

        /**
         * If another field is set to given value OR set of values, and the current 
         * value is not present, return an error message.
         */
        if (!(is_null($value) && str_contains($valuesOfOtherField, $this->data[$otherField]))) {
            return "The field {$attribute} is required when the field {$otherField} is set to {$this->data[$otherField]}.";
        }

        return true;
    }

    /**
     * Validate the data against the given closure expression.
     *
     * @param   string  $attribute
     * @param   mixed   $value
     * 
     * @return  bool|string
     */
    protected function assetCallbackExpression(string $attribute, mixed $value): bool|string
    {
        $result = call_user_func($this->expression, $attribute, $value);

        if (is_string($result) || $result === false) {
            return $result;
        }

        return true;
    }
}
