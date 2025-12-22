<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class NumEq extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected int|float|string $allowedValue)
    {
        if (\filter_var($allowedValue, FILTER_VALIDATE_FLOAT) === false || \filter_var($allowedValue, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule num_eq is invalid.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (\filter_var($value, FILTER_VALIDATE_FLOAT) === false || \filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->error = 'The field :{field} must be a valid number';
            return false;
        }

        $this->error = "The field :{field} must be exactly equal to {$this->allowedValue}";
        return (string) $value === (string) $this->allowedValue;
    }

    public function error(): string
    {
        return $this->error;
    }
}
