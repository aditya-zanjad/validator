<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRule;

class Req extends AbstractRule implements MandatoryRule
{
    public function validate(mixed $value): bool
    {
        if ((\is_string($value) || \is_array($value)) && empty($value)) {
            return false;
        }

        if (\is_null($value)) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} is required.';
    }
}
