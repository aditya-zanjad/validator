<?php

namespace AdityaZanjad\Validator\Rules\Constraints;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class RequiredUnless implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (is_null($value)) {
            return "The field {$attribute} is required";
        }

        return true;
    }
}
