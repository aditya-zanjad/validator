<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Filled extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $valueIsFilled = !empty($value);

        if (is_string($value) && is_file($value) && in_array(filesize($value), [0, false])) {
            return "The file {$field} must not be empty.";
        }

        if ($valueIsFilled) {
            return true;
        }

        switch (gettype($value)) {
            case 'string':
                return "The string {$field} must not be empty.";

            case 'array':
                return "The array {$field} must not be empty.";

            case 'boolean':
                return true;

            case 'NULL':
                return "the field {$field} must not be empty or NULL";

            default:
                return "The field {$field} must not be empty.";
        }
    }
}
