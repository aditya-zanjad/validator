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
    public function check(string $field, mixed $value): bool
    {
        return \is_string($value)
            && \strlen($value) === 26
            && (bool) preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/', $value);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid ULID string.';
    }
}
