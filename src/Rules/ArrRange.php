<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ArrRange extends AbstractRule
{
    protected string $error = 'The field :{field} must be valid.';

    public function __construct(protected int $minRequiredLength, protected int $maxAllowedLength)
    {
        if ($minRequiredLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule arr_range [{$currentClassName}] must be valid.");
        }

        if ($maxAllowedLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule arr_range [{$currentClassName}] must be valid.");
        }

        if ($minRequiredLength >= $maxAllowedLength) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter max length supplied to the validation rule arr_range [{$currentClassName}] must be greater than the parameter max length.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        $currentArrLength = \count($value);

        if ($currentArrLength < $this->minRequiredLength || $currentArrLength > $this->maxAllowedLength) {
            $this->error = "The field :{field} must contain number of elements in the range [{$this->minRequiredLength} - {$this->maxAllowedLength}].";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be an array.';
    }
}
