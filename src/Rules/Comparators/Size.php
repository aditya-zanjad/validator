<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Size implements ValidationRule
{
    /**
     * Inject necessary data into the class.
     *
     * @param mixed $allowedValue
     */
    public function __construct(protected mixed $allowedValue)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if ($value !== $this->allowedValue) {
            return "The attribute {$attribute} must be exactly equal to {$this->allowedValue}.";
        }

        return true;
    }
}
