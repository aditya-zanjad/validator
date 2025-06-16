<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsGreaterOrEqual extends AbstractRule
{
    /**
     * @var int $minimumDigitsRequired
     */
    protected int $minimumDigitsRequired;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $minimumDigitsRequired
     *
     * @throws \Exception
     */
    public function __construct($minimumDigitsRequired)
    {
        if (!filter_var($minimumDigitsRequired, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_gte] must be the valid integer.");
        }

        $this->minimumDigitsRequired = (int) $minimumDigitsRequired;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_numeric($value)) {
            return "The field {$field} must be a valid numeric value.";
        }

        if (varDigits($value) > $this->minimumDigitsRequired) {
            return "The field {$field} must contain minimum {$this->minimumDigitsRequired} digits.";
        }

        return true;
    }
}
