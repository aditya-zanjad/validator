<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class Digits extends AbstractRule
{
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
        if (!\filter_var($validDigitsCount, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits] must be a valid integer.");
        }

        $this->validDigitsCount = (int) $validDigitsCount;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!\is_numeric($value)) {
            return "The field {$field} must be a valid numeric value.";
        }

        if (varDigits($value) !== $this->validDigitsCount) {
            return "The field {$field} must contain exactly {$this->validDigitsCount} digits.";
        }

        return true;
    }
}
