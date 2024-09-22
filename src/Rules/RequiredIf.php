<?php

namespace AdityaZanjad\Validator\Rules;

use Closure;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute should be applied the validation rule "required" or not.
 */
class RequiredIf implements ValidationRule
{
    /**
     * Inject necessary dependency into the class.
     *
     * @param Closure $expression
     */
    public function __construct(protected Closure $expression)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        $result = call_user_func($this->expression, $attribute, $value);

        if (is_string($result) || $result === false) {
            return $result;
        }

        return true;
    }
}
