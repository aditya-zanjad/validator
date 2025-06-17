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
    public function check(string $field, $value)
    {
        if (!$this->input->notNull($field)) {
            return "The field {$field} is required.";
        }

        return true;
    }
}
