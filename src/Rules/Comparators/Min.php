<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Min implements ValidationRule
{
    /**
     * Inject necessary data into the class.
     *
     * @param mixed $minAllowedValue
     */
    public function __construct(protected mixed $minAllowedValue)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if ($value < $this->minAllowedValue) {
            return "The field {$attribute} must not be less than {$this->minAllowedValue}.";
        }

        return true;
    }
}
