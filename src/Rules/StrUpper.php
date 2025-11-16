<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class StrUpper extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        if (\function_exists('\\mb_strtoupper') && mb_strtoupper($value) !== $value) {
            $this->error = 'The field :{field} must contain only the uppercase letters.';
            return false;
        }

        if (\strtoupper($value) !== $value) {
            $this->error = 'The field :{field} must contain only the uppercase letters.';
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
