<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class StrRange extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected int $minValidLength, protected int $maxValidLength)
    {
        if ($minValidLength < 0 || $maxValidLength < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule str_range [{$currentClassName}] are invalid.");
        }

        if ($minValidLength >= $maxValidLength) {
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

        if ($currentValueLength <= $this->minValidLength || $currentValueLength >= $this->maxValidLength) {
            $this->error = "The field :{field} must contain characters in the allowed range [{$this->minValidLength} - {$this->maxValidLength}].";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
