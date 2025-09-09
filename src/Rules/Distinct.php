<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Distinct extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        // TODO => Add validation for recognizing if the array contains distinct values or not

        return true;
    }
}
