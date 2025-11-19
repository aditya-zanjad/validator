<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class StrRegex extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function __construct(protected string $regex)
    {
        if (empty($regex)) {
            throw new Exception("[Developer][Exception]: The parameter to the ");
        }
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        $result = \preg_match($this->regex, $value);

        if ($result === false) {
            $className = static::class;
            throw new Exception("[Developer][Exception]: The regex you've passed to the validation rule str_regex [{$className}] is invalid.");
        }

        if ($result === 0) {
            $this->error = "The field :{field} must match with the regex pattern {$this->regex}";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
