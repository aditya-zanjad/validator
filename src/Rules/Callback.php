<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Callback extends AbstractRule
{
    /**
     * To decide the error message dynamically.
     *
     * @var string $message
     */
    protected string $message = 'The field :{field} is invalid';

    /**
     * To contain & execute the callback function.
     *
     * @var callable $fn
     */
    protected $fn;

    /**
     * @param callable(string $field, mixed $value, \AdityaZanjad\Validator\Fluents\Input $input): bool|string $fn
     */
    public function __construct(callable $fn)
    {
        $this->fn = $fn;
    }

    /**
     * @inheritDoc
     * 
     * @throws \Exception => Whenever the callback function returns an invalid value.
     */
    public function check(string $field, $value): bool
    {
        $result = ($this->fn)($field, $value, $this->input);

        if (\is_string($result)) {
            $this->message = $result;   
            return false;
        }

        if (\is_bool($result)) {
            return $result;
        }

        throw new Exception("[Developer][Exception]: The callback validation rule for the field [{$field}] must return either a [BOOLEAN] or a [STRING] value.");
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}