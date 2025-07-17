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
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be a valid MAC address.';
        }

        if (function_exists('\\filter_var') && filter_var($value, FILTER_VALIDATE_MAC) === false) {
            return 'The field :{field} must be a valid MAC address.';
        }

        return true;
    }
}
