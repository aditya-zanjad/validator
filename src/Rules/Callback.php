<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Closure;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class Callback extends AbstractRule
{
    protected string $message = 'The field :{field} is invalid';

    public function __construct(protected Closure $fn)
    {
        //
    }

    public function validate(mixed $value): bool
    {
        $result = $this->{'fn'}($value, $this->field, $this->input);

        if (\is_string($result)) {
            $this->message = $result;
            return false;
        }

        if (!\is_bool($result)) {
            throw new Exception("[Developer][Exception]: The callback validation rule must always return either a STRING or a BOOLEAN value.");
        }

        return $result;
    }

    public function error(): string
    {
        return $this->message;
    }
}
