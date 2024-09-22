<?php

namespace AdityaZanjad\Validator\Rules;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Required
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (in_array($value, ['', [], null])) {
            return "The field {$attribute} is required";
        }

        return true;
    }
}
