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
     * @var int $validDigits
     */
    protected int $validDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $validDigits
     *
     * @throws \Exception
     */
    public function __construct($validDigits)
    {
        if (!\filter_var($validDigits, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits] must be a valid integer.");
        }

        $this->validDigits = (int) $validDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $digits = varDigits($value);
        return !\is_null($digits) && $digits === $this->validDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain exactly {$this->validDigits} digits.";
    }
}
