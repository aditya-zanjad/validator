<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class UpperCase extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!\is_string($value)) {
            return 'The field :{field} must be an uppercase string.';
        }

        if (\function_exists('preg_match') && \preg_match('/^[A-Z0-9!@#$%^&*()_+\-=\[\]{}|;:\'",.<>\/?`~]+$/', $value) === false) {
            return 'The field :{field} must be an uppercase string.';
        }

        if (\strtoupper($value) !== $value) {
            return 'The field :{field} must be an uppercase string.';
        }

        return true;
    }
}
