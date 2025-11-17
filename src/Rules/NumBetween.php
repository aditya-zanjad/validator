<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class NumBetween extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function __construct(protected int|float $minInvalidValue, protected int|float $maxInvalidValue)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        if (\filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            $this->error = 'The field :{field} must be a valid number.';
            return false;
        }

        if ($value <= $this->minInvalidValue || $value >= $this->maxInvalidValue) {
            $this->error = "The field :{field} must be between the values [{$this->minInvalidValue} - {$this->maxInvalidValue}]";
            return false;
        }

        return true;
    }
}
