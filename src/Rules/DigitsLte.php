<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsLte extends AbstractRule
{
    /**
     * @var int $maxAllowedDigits
     */
    protected int $maxAllowedDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $maxAllowedDigits
     *
     * @throws \Exception
     */
    public function __construct($maxAllowedDigits)
    {
        if (filter_var($maxAllowedDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_lte] must be the valid integer.");
        }

        $this->maxAllowedDigits = (int) $maxAllowedDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false && varDigits($value) <= $this->maxAllowedDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} cannot contain more than {$this->maxAllowedDigits} digits.";
    }
}
