<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class Required extends AbstractRule implements RequisiteRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return $this->input->notNull($field);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} is required.";
    }
}
