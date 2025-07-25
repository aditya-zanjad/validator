<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Email extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a valid email address.";
    }
}
