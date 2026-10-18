<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

abstract class AbstractRule implements ValidationRule
{
    protected Input $input;

    protected string $field;

    public function setFieldName(string $fieldName): static
    {
        $this->field = $fieldName;
        return $this;
    }

    public function setInputInstance(Input $input): static
    {
        $this->input = $input;
        return $this;
    }

    protected string $error = "The field :{field} is invalid.";

    public function error(): string
    {
        return $this->error;
    }
}
