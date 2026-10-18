<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRule;

class ReqWithoutAll extends AbstractRule implements MandatoryRule
{
    protected array $dependencyFields = [];

    protected string $error = 'The field :{field} is invalid';

    public function __construct(string ...$dependencyFields)
    {
        if (empty($dependencyFields)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The rule required_without_all [{$currentClassName}] requires at least one parameter.");
        }

        $this->dependencyFields = $dependencyFields;
    }

    public function validate(mixed $value): bool
    {
        foreach ($this->dependencyFields as $field) {
            if ($this->input->isset($field)) {
                return true;
            }
        }

        if (\is_null($value)) {
            $dependencyFieldsJoined = \implode(', ', $this->dependencyFields);
            $this->error            = "The field :{field} is required when none of {$dependencyFieldsJoined} are present.";

            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}