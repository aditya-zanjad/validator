<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Closure;
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
     * @var \Closure $fn
     */
    protected Closure $fn;

    /**
     * @param \Closure $fn
     */
    public function __construct(Closure $fn)
    {
        $this->fn = $fn;
    }

    /**
     * @inheritDoc
     * 
     * @throws \Exception => Whenever the callback function returns an invalid value.
     */
    public function check(mixed $value): bool
    {
        try {
            $result = ($this->fn)($value, $this->field, $this->input);
        } catch (InvalidArgumentException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The field [{$this->field}] has a callback validation rule passed with invalid argument(s).");
        }

        if (\is_bool($result)) {
            return $result;
        }

        if (!\is_string($result)) {
            throw new Exception("[Developer][Exception]: The field [{$this->field}] has a callback validation rule which must return either a [BOOLEAN] or a [STRING] value.");
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
