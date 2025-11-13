<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Str extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        return \is_string($value);
    }

    public function error(): string
    {
        return "The field :{field} must be a string.";
    }
}
