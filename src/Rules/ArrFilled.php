<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class ArrFilled extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        if (\count($value) < 1) {
            $this->error = 'The field :{field} must contain at least one element.';
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be an array.';
    }
}
