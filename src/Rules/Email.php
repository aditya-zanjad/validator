<?php

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Rules\Rule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Email extends Rule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "The field {$attribute} must be a valid email address.";
        }

        return true;
    }
}
