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
    public function check(string $field, mixed $value): bool|string
    {
        if ($value === '' || $value === []) {
            return "The field {$field} must not be empty.";
        }

        if (is_file($value) && in_array(filesize($value), [0, false])) {
            return "The file {$field} must not be empty.";
        }

        return true;
    }
}
