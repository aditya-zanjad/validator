<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Interfaces;

use AdityaZanjad\Validator\Input;

interface ValidationRule
{
    public function setFieldName(string $fieldName): static;

    public function setInputInstance(Input $input): static;

    public function validate(mixed $value): bool;

    public function error(): string;
}
