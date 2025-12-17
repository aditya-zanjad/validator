<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Same extends AbstractRule
{
    public function __construct(protected string $otherField)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        return $value === $this->input->get($this->otherField);
    }

    public function error(): string
    {
        return "The value of the field :{field} must be the same as the field {$this->otherField}.";
    }
}
