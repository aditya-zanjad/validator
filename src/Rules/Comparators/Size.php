<?php

namespace AdityaZanjad\Validator\Rules\Comparators;

use InvalidArgumentException;
use AdityaZanjad\Validator\Rules\Rule;

use function AdityaZanjad\Validator\Utils\{size_of, filter_value};

/**
 * Check whether the given attribute is a valid string or not.
 */
class Size extends Rule
{
    /**
     * Inject necessary data into the class.
     *
     * @param mixed $expectedValue
     */
    public function __construct(protected mixed $expectedValue)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if ($this->expectedValue === '') {
            throw new InvalidArgumentException("[Developer][Exception]: The validation rule [size] for the field [{$attribute}] must be provided with a single numeric argument.");
        }

        $size                   =   size_of($value, "[Developer][Exception]: The field [{$attribute}] must be either in [INT], [FLOAT], [STRING] OR [ARRAY] to be able to calculate its size.");
        $this->expectedValue    =   filter_value($this->expectedValue);

        /**
         * The operator '!=' is used instead of '!==' intentionally to make sure that in case of
         * left operand being an integer & right operand being a float, we're able to match
         * their values regardless of their data types.
         */
        if ($size != $this->expectedValue) {
            return "The attribute {$attribute} must be exactly equal to {$this->expectedValue}.";
        }

        return true;
    }
}
