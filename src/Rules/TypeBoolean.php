<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeBoolean extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!filter_var($value, FILTER_VALIDATE_BOOL)) {
            return "The field {$field} must be a boolean value.";
        }

        return true;
    }
}
