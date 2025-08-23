<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use InvalidArgumentException;
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
     * @var callable(string $field, mixed $value, \AdityaZanjad\Validator\Fluents\Input $input): bool $fn
     */
    protected $fn;

    /**
     * @param callable(string $field, mixed $value, \AdityaZanjad\Validator\Fluents\Input $input): bool $fn
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
        try {
            $result = ($this->fn)($field, $value, $this->input);
        } catch (InvalidArgumentException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The field [{$field}] has callback validation rule(s) passed with invalid argument(s).");
        }

        if (\is_bool($result)) {
            return $result;
        }

        if (\is_string($result)) {
            throw new Exception("[Developer][Exception]: The field [{$field}] has callback validation that must return either a [BOOLEAN] or a [STRING] value.");
        }
        
        $this->message = $result;
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
