<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Number extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        return \filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    public function error(): string
    {
        return 'The field :{field} must be a numeric value.';
    }
}
