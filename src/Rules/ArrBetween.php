<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ArrBetween extends AbstractRule
{
    protected string $error = 'The field :{field} must be valid.';

    public function __construct(protected int $minRequiredLength, protected int $maxAllowedLength)
    {
        if ($minRequiredLength < 0) {
            throw new Exception("[Developer][Exception]: The parameter [min_length] supplied to the validation rule [arr_between] must be valid.");
        }

        if ($maxAllowedLength < 0) {
            throw new Exception("[Developer][Exception]: The parameter [max_length] supplied to the validation rule [arr_between] must be valid.");
        }

        if ($minRequiredLength >= $maxAllowedLength) {
            throw new Exception("[Developer][Exception]: The parameter [max_length] supplied to the validation rule [arr_between] must be greater than the parameter [max_length].");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        $currentArrLength = \count($value);

        if ($currentArrLength <= $this->minRequiredLength || $currentArrLength >= $this->maxAllowedLength) {
            $this->error = "The field :{field} must contain elements between the limit {$this->minRequiredLength} to {$this->maxAllowedLength}.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
