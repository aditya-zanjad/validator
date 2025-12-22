<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ArrSize extends AbstractRule
{
    protected int $allowedSize;

    protected string $error = 'The field :{field} is invalid.';

    public function __construct(int|string $allowedSize)
    {
        if (\filter_var($allowedSize, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule [arr_size] is invalid.");
        }

        $this->allowedSize = (int) $allowedSize;
    }

    public function validate(mixed $value): bool
    {
        if (!\is_array($value)) {
            $this->error = 'The field :{field} must be an array.';
            return false;
        }

        $this->error = "The field :{field} must contain exactly {$this->allowedSize} elements.";
        return \count($value) === $this->allowedSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
