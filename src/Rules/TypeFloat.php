<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeFloat extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return "The field {$field} must be a float number.";
        }

        return true;
    }
}
