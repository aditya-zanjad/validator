<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeArray extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_array($value)) {
            return "The field {$field} must be an array.";
        }

        return true;
    }
}
