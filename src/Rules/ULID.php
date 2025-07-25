<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class ULID extends AbstractRule
{
    public function __construct()
    {
        
    }

    public function check(string $field, $value): bool
    {
        // TODO => Implement logic for ULID validation.

        if (!\is_string($value)) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return 'The field :{field} must be a valid ULID string.';
    }
}