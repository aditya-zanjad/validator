<?php

namespace AdityaZanjad\Validator\Rules\Required;

use AdityaZanjad\Validator\Interfaces\RequiredRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Required implements RequiredRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (is_null($value)) {
            return 'The field {:attribute} is required.';
        }

        return true;
    }
}
