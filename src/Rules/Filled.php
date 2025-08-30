<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varArrSize;
use function AdityaZanjad\Validator\Utils\varStrSize;
use function AdityaZanjad\Validator\Utils\varFileSize;

/**
 * @version 1.0
 */
class Filled extends AbstractRule
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        if (\is_bool($value) || \filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return true;
        }

        $valueType = \gettype($value);

        $result = match ($valueType) {
            'string'    =>  varStrSize($value),
            'array'     =>  varArrSize($value),
            'resource'  =>  varFileSize($value),
            default     =>  true
        };

        if (\is_null($result) || $result === 0) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be filled.';
    }
}
