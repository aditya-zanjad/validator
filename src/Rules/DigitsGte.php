<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsGte extends AbstractRule
{
    /**
     * @var int $minDigits
     */
    protected int $minDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $minDigits
     *
     * @throws \Exception
     */
    public function __construct($minDigits)
    {
        if (filter_var($minDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_gte] must be the valid integer.");
        }

        $this->minDigits = (int) $minDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $digits = varDigits($value);
        return !\is_null($digits) && varDigits($value) >= $this->minDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain minimum {$this->minDigits} digits.";
    }
}
