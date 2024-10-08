<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeNum implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return "The field {$attribute} must be a numeric value.";
        }

        return true;
    }
}
