<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class NumRange extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function __construct(protected int|float $minRequiredValue, protected int|float $maxAllowedValue)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        if (\filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            $this->error = 'The field :{field} must be a valid number.';
            return false;
        }

        if ($value < $this->minRequiredValue || $value > $this->maxAllowedValue) {
            $this->error = "The field :{field} must be in the range [{$this->minRequiredValue} - {$this->maxAllowedValue}]";
            return false;
        }

        return true;
    }
}
