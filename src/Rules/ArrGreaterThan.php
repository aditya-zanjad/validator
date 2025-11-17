<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ArrGreaterThan extends AbstractRule
{
    protected string $error = 'The field :{field} must be valid.';

    public function __construct(protected int $minInvalidLength)
    {
        if ($minInvalidLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule arr_gt [{$currentClassName}] must be valid.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        if (\count($value) <= $this->minInvalidLength) {
            $this->error = "The field :{field} must contain more than {$this->minInvalidLength} elements.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be an array.';
    }
}
