<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Arr extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        return \is_array($value);
    }

    public function error(): string
    {
        return 'The field :{field} must be an array';
    }
}
