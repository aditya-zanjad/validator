<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class NumRange extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function __construct(protected int|float|string $minAllowedValue, protected int|float|string $maxAllowedValue)
    {
        if (\filter_var($minAllowedValue, FILTER_VALIDATE_FLOAT) === false || \filter_var($minAllowedValue, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter min allowed value passed to the validation rule num_range is invalid.");
        }

        if (\filter_var($maxAllowedValue, FILTER_VALIDATE_FLOAT) === false || \filter_var($maxAllowedValue, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter max allowed value passed to the validation rule num_range is invalid.");
        }

        if ($minAllowedValue > $maxAllowedValue) {
            throw new Exception("[Developer][Exception]: The parameter min allowed value must not be greater than the parameter max allowed value passed to the validation rule num_range.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (\filter_var($value, FILTER_VALIDATE_FLOAT) !== false || \filter_var($value, FILTER_VALIDATE_INT) !== false) {
            $this->error = 'The field :{field} must be a valid number.';
            return false;
        }

        if ($value < $this->minAllowedValue || $value > $this->maxAllowedValue) {
            $this->error = "The field :{field} must be in the range [{$this->minAllowedValue} - {$this->maxAllowedValue}]";
            return false;
        }

        return true;
    }
}
