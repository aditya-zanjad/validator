<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class StrMin extends AbstractRule
{
    protected int|string $minValidLength;

    protected string $error = 'The field :{field} is invalid';

    public function __construct(int|string $minValidLength)
    {
        if (\filter_var($minValidLength, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [str_min] must be an integer value.");
        }

        $minValidLength = (int) $minValidLength;

        if ($minValidLength < 0) {
            throw new Exception("[Developer][Exception]: The parameter paased to the validation rule [str_min] must not be less than 0.");
        }

        $this->minValidLength = $minValidLength;
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            $this->error = 'The field :{field} must be a string.';
            return false;
        }

        if (\strlen($value) < $this->minValidLength) {
            $this->error = "The field :{field} must contain at least {$this->minValidLength} characters.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
