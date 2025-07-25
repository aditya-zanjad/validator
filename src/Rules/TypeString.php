<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeString extends AbstractRule
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @var string $regex
     */
    protected string $regex;

    /**
     * @param string $regex
     */
    public function __construct(string $regex = '')
    {
        $this->regex = $regex;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        if (!\is_string($value)) {
            $this->message = "The field {$field} must be a string.";
            return false;
        }

        if (!empty($this->regex) && \preg_match($this->regex, $value) === false) {
            $this->message = "The field :{field} must be string matching with the regex {$this->regex}.";
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
