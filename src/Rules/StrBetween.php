<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class StrBetween extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected int $minInvalidLength, protected int $maxInvalidLength)
    {
        if ($minInvalidLength < 0 || $maxInvalidLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule str_range [{$currentClassName}] are invalid.");
        }

        if ($minInvalidLength >= $maxInvalidLength) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The min length parameter passed to the validation rule str_range [{$currentClassName}] must be less than the max length parameter..");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        $currentValueLength = \strlen($value);

        if ($currentValueLength < $this->minInvalidLength || $currentValueLength > $this->maxInvalidLength) {
            $this->error = "The field :{field} must contain the characters between [{$this->minInvalidLength} - {$this->maxInvalidLength}] characters.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
