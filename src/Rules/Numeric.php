<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Numeric extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        if (\is_bool($value)) {
            return false;
        }

        if (\extension_loaded('filter')) {
            return \filter_var($value, FILTER_VALIDATE_FLOAT) !== false || \filter_var($value, FILTER_VALIDATE_INT) !== false; 
        }
        
        return \is_numeric($value);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a valid number.";
    }
}
