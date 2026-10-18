<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Ulid extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        return \preg_match('/^[0-7][0-9A-HJKMNP-TV-Z]{25}$/i', $value) === 1;
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid ULID.';
    }
}
