<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Rules\Rule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeBool extends Rule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_BOOL)) {
            return "The field {$attribute} must be a boolean value.";
        }

        return true;
    }
}
