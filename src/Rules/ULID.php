<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class ULID extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        if (\strlen($value) !== 26) {
            return false;
        }

        return preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/', $value) > 0;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid ULID string.';
    }
}
