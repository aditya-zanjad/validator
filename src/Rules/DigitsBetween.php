<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsBetween extends AbstractRule
{
    /**
     * @var int $minDigits
     */
    protected int $minDigits;

    /**
     * @var int $maxDigits
     */
    protected int $maxDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param   int|string  $minDigits
     * @param   int|string  $maxDigits
     *
     * @throws  \Exception
     */
    public function __construct($minDigits, $maxDigits)
    {
        if (filter_var($minDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [digits_between] must be the valid integer.");
        }

        if (filter_var($minDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [digits_between] must be the valid integer.");
        }

        $this->minDigits    =   (int) $minDigits;
        $this->maxDigits    =   (int) $maxDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $digits = varDigits($value);
        return !\is_null($digits) && $digits >= $this->minDigits && $digits <= $this->maxDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain the digits between {$this->minDigits} & {$this->maxDigits} digits.";
    }
}
