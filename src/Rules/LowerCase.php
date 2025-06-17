<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class LowerCase extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be a lowercase string.';
        }

        if (function_exists('preg_match') && preg_match('/^[a-z0-9!@#$%^&*()_+\-=\[\]{}|;:\'",.<>\/?`~]+$/', $value) === false) {
            return 'The field :{field} must be a lowercase string.';
        }

        if (strtolower($value) !== $value) {
            return 'The field :{field} must be a lowercase string.';
        }

        return true;
    }
}
