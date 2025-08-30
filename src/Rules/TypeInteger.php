<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeInteger extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be an integer value.";
    }
}
