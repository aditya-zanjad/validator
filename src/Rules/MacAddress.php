<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class MacAddress extends AbstractRule
{
    protected string $regex;

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_MAC) !== false; 
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid MAC address.';
    }
}
