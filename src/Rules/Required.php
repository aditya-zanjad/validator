<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Required extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        return !\is_null($value);
    }

    public function error(): string
    {
        return 'The field :{field} is required.';
    }
}
