<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use Throwable;
use Stringable;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeString extends AbstractRule
{
    /**
     * The Regular Expression to apply on the string.
     * 
     * It can be either a valid regular expression or it can be an empty string. As a valid
     * regular expression, it'll be applied to validate the string. If it's an empty
     * string, its validation will be skipped.
     * 
     * @var string $regex
     */
    protected string $regex;

    /**
     * To contain the validation error message.
     *
     * @var string $message
     */
    protected string $message;

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
    public function check(string $field, mixed $value): bool
    {
        if (!\is_string($value) && !$value instanceof Stringable) {
            $this->message = 'The field :{field} must be a string.';
            return false;
        }

        if (empty($this->regex)) {
            return true;
        }

        try {
            $value = (string) $value;
        } catch (Throwable $e) {
            throw new Exception("[Developer][Exception]: Failed to interpret the field [{$field}] as a stringable object. Check its [__toString()] method is working correctly.");
        }

        if (!\preg_match($this->regex, $value)) {
            $this->message = "The field :{field} must match the regular expression: {$this->regex}.";
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
