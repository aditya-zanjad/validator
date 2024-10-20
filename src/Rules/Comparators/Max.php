<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use InvalidArgumentException;
use AdityaZanjad\Validator\Rules\Rule;

use function AdityaZanjad\Validator\Utils\{size_of, filter_value};

/**
 * Check whether the given attribute is a valid string or not.
 */
class Max extends Rule
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
        if ($this->maxAllowedValue === '') {
            throw new InvalidArgumentException("[Developer][Exception]: The validation rule [max] for the field [{$attribute}] must be provided with a single numeric argument.");
        }

        $size = size_of($value, "[Developer][Exception]: The field [{$attribute}] must be either in [INT], [FLOAT], [STRING] OR [ARRAY] format to be able to calculate its size.");

        if ($size > filter_value($this->maxAllowedValue)) {
            return "The field {$attribute} must not be greater than {$this->maxAllowedValue}.";
        }

        return true;
    }
}
