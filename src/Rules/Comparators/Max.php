<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class Max implements ValidationRule
{
    /**
     * Inject necessary data into the class.
     *
     * @param mixed $maxAllowedValue
     */
    public function __construct(protected mixed $maxAllowedValue)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if ($value > $this->maxAllowedValue) {
            return "The field {$attribute} must not be greater than {$this->maxAllowedValue}.";
        }

        return true;
    }
}
