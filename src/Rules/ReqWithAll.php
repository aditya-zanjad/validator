<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class ReqWithAll extends AbstractRule
{
    protected array $dependencyFields = [];

    protected string $error = 'The field :{field} is invalid';

    public function __construct(string ...$dependencyFields)
    {
        if (empty($dependencyFields)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The rule required_with [{$currentClassName}] requires at least one parameter.");
        }

        $this->dependencyFields = $dependencyFields;
    }

    public function validate(mixed $value): bool
    {
        foreach ($this->dependencyFields as $field) {
            if (!$this->input->isset($field)) {
                return true;
            }
        }

        if (\is_null($value)) {
            $this->error = "The field :{field} is required with the field {$field}";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}