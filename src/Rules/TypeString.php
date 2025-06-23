<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeString extends AbstractRule
{
    protected string $regex;

    public function __construct(string $regex = '')
    {
        $this->regex = $regex;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!\is_string($value)) {
            return "The field {$field} must be a string.";
        }

        if (empty($this->regex)) {
            return true;
        }

        if (\preg_match($this->regex, $value) === false) {
            return "The field :{field} must be string matching with the regex {$this->regex}.";
        }

        return true;
    }
}
