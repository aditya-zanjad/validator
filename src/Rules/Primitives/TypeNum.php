<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Rules\Rule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeNum extends Rule
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
