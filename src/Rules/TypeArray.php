<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use ArrayAccess;
use ArrayObject;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeArray extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        if (\is_array($value)) {
            return true;
        }

        if ($value instanceof ArrayObject) {
            return true;
        }

        if ($value instanceof ArrayAccess) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The field :{field} must be an array.';
    }
}
