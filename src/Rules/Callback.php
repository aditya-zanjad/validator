<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Closure;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class Callback extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected Closure $fn)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        $result = \call_user_func($this->fn, $this->field, $this->input);

        if (\is_string($result)) {
            $this->error = $result;
            return false;
        }

        if (!\in_array($result, [true, false], true)) {
            throw new Exception("[Developer][Exception]: The callback validation rule must always return either a STRING or a BOOLEAN value.");
        }

        return $result;
    }

    public function error(): string
    {
        return $this->error;
    }
}
