<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use InvalidArgumentException;
use AdityaZanjad\Validator\Rules\Rule;

use function AdityaZanjad\Validator\Utils\{size_of, filter_value};

/**
 * Check whether the given attribute is a valid string or not.
 */
class Min extends Rule
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
        if ($this->minAllowedValue === '') {
            throw new InvalidArgumentException("[Developer][Exception]: The validation rule [min] for the field [{$attribute}] must be provided with a single numeric argument");
        }

        $size = size_of($value, "[Developer][Exception]: The field [{$attribute}] must be either in [INT], [FLOAT], [STRING] OR [ARRAY] format to be able to calculate its size.");

        if ($size < filter_value($this->minAllowedValue)) {
            return "The field {$attribute} must not be less than {$this->minAllowedValue}.";
        }

        return true;
    }
}
