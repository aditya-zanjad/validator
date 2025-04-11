<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\VarHelpers;

/**
 * @version 1.0
 */
class Digits extends AbstractRule
{
    use VarHelpers;

    /**
     * @var int $validDigitsCount
     */
    protected int $validDigitsCount;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $validDigitsCount
     *
     * @throws \Exception
     */
    public function __construct($validDigitsCount)
    {
        if (!filter_var($validDigitsCount, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter to the validation rule [" . static::class . "] must be either an Integer OR a String.");
        }

        $this->validDigitsCount = (int) $validDigitsCount;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_numeric($value)) {
            return "The field {$field} must be a valid numeric value.";
        }

        if ($this->varDigits($value) !== $this->validDigitsCount) {
            return "The field {$field} must contain exactly {$this->validDigitsCount} digits.";
        }

        return true;
    }
}
