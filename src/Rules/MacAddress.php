<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class MacAddress extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value): bool
    {
        return \is_string($value) && \filter_var($value, FILTER_VALIDATE_MAC) !== false; 
    }

    /**
     * @return string
     */
    public function error(): string
    {
        return 'The field :{field} must be a valid MAC address.';
    }
}