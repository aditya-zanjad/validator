<?php

namespace AdityaZanjad\Validator\Rules\Primitives;

use AdityaZanjad\Validator\Rules\Rule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class TypeFile extends Rule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (is_string($value) && !is_resource($value) && file_exists($value)) {
            $value = '';
        }

        return true;
    }
}
