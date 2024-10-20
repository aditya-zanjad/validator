<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use InvalidArgumentException;
use AdityaZanjad\Validator\Rules\Rule;

use function AdityaZanjad\Validator\Utils\filter_values;

/**
 * Check whether the given attribute is a valid string or not.
 */
class In extends Rule
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
        if (empty($allowedValues)) {
            throw new InvalidArgumentException("[Developer][Exception]: The validation rule [" . static::class . "] expects at least one argument.");
        }

        $this->allowedValues = $allowedValues;
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (in_array($value, filter_values(...$this->allowedValues), true)) {
            return true;
        }

        return count($this->allowedValues) > 1
            ? "The field {$attribute} must be equal to either of the [" . implode(', ', $this->allowedValues) . "]."
            : "The field {$attribute} must be equal to {$this->allowedValues[0]}.";
    }
}
