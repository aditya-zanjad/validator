<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Url extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return "The field {$field} must be a valid URL.";
        }

        return true;
    }
}
