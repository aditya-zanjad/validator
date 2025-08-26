<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Filled extends AbstractRule
{
    /**
     * @var string $message
     */
    protected string $message = 'The field :{field} is invalid.';

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        if (!empty($value) || (extension_loaded('filter') && \filter_var($value, FILTER_VALIDATE_BOOL)) || \is_bool($value)) {
            return true;
        }

        $this->message = match (\gettype($value)) {
            'string'    =>  \is_file($value) && \in_array(filesize($value), [0, false]) ? "The file :{field} must not be empty." : "The string :{field} must not be empty.",
            'array'     =>  "The array :{field} must not be empty.",
            'NULL'      =>  "the field :{field} must not be an empty or a NULL value.",
            'default'   =>  "The field :{field} must not be empty.",
        };

        return false;
    }

    /**
    * @inheritDoc
    */
    public function message(): string
    {
        return $this->message;
    }
}
