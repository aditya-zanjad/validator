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
    public function check(string $field, mixed $value): bool
    {
        return \is_array($value) || $value instanceof ArrayObject || $value instanceof ArrayAccess;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The field :{field} must be an array.';
    }
}
