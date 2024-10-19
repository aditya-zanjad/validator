<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Rules\Rule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeStr extends Rule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field {$attribute} must be a string.";
        }

        return true;
    }
}
