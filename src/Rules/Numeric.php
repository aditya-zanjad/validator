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
    public function check(string $field, mixed $value): bool|string
    {
        if (!filter_var($field, FILTER_VALIDATE_INT) || !filter_var($field, FILTER_VALIDATE_FLOAT)) {
            return "The field {$field} must be a valid number.";
        }

        return true;
    }
}
