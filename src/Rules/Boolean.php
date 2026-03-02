<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Boolean extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        if (\is_string($value) && \strlen($value) === 0) {
            return false;
        }

        return !\is_null(\filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE));
    }

    public function error(): string
    {
        return 'The field :{field} must be a boolean value.';
    }
}
