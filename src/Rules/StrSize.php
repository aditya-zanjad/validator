<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class StrSize extends AbstractRule
{
    protected int $allowedSize;

    protected string $error = 'The field :{field} is invalid.';

    public function __construct(int|string $allowedSize)
    {
        if (\filter_var($allowedSize, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule [str_size] is invalid.");
        }

        $this->allowedSize = (int) $allowedSize;
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        $this->error = "The field :{field} must contain exactly {$this->allowedSize} characters.";

        if (\function_exists('mb_strlen') && \mb_strlen($value) === $this->allowedSize) {
            return true;
        }

        if (\strlen($value) === $this->allowedSize) {
            return true;
        }

        return false;
    }

    public function error(): string
    {
        return $this->error;
    }
}