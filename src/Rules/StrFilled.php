<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class StrFilled extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        if (\is_null($value) || !\is_string($value)) {
            return false;
        }

        if (\extension_loaded('mbstring') && \function_exists('\\mb_strlen') && \mb_strlen($value) < 1) {
            return false;
        }

        if (\strlen($value)) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be filled.';
    }
}
