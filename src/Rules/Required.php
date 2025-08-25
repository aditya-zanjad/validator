<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRuleInterface;

/**
 * @version 1.0
 */
class Required extends AbstractRule implements MandatoryRuleInterface
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return !\is_null($value);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} is required.";
    }
}
