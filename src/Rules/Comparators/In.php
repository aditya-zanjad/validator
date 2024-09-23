<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class In implements ValidationRule
{
    /**
     * To contain the variable level of arguments.
     *
     * @var array<int, mixed> $allowedValues
     */
    protected array $allowedValues;

    /**
     * Inject necessary data into the class.
     *
     * @param mixed ...$allowedValues
     */
    public function __construct(mixed ...$allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!in_array($value, $this->allowedValues)) {
            return "The field {$attribute} must be either of [" . implode(', ', $this->allowedValues) . "].";
        }

        return true;
    }
}
