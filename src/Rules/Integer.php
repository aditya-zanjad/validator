<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Integer extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        return \filter_var($value, FILTER_VALIDATE_INT);
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid integer value.';
    }
}
