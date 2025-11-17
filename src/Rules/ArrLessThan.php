<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ArrLessThan extends AbstractRule
{
    protected string $error = 'The field :{field} must be valid.';

    public function __construct(protected int $maxInvalidLength)
    {
        if ($maxInvalidLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule arr_lt [{$currentClassName}] must be valid.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        if (\count($value) <= $this->maxInvalidLength) {
            $this->error = "The field :{field} must not contain more than {$this->maxInvalidLength} elements.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be an array.';
    }
}
