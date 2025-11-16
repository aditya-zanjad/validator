<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class StrGreaterThan extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected int $minInvalidLength)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        if (\strlen($value) > $this->minInvalidLength) {
            $this->error = "The field :{field} must contain more than {$this->minInvalidLength} characters.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
