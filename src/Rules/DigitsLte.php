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
     * @var int $maxDigits
     */
    protected int $maxDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $maxDigits
     *
     * @throws \Exception
     */
    public function __construct($maxDigits)
    {
        if (filter_var($maxDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_lte] must be the valid integer.");
        }

        $this->maxDigits = (int) $maxDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $digits = varDigits($value);
        return !\is_null($digits) && $digits <= $this->maxDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} cannot contain more than {$this->maxDigits} digits.";
    }
}
