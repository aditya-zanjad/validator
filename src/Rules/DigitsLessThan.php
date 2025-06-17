<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsLessThan extends AbstractRule
{
    /**
     * @var int $maxThreshold
     */
    protected int $maxThreshold;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $maxThreshold
     *
     * @throws \Exception
     */
    public function __construct($maxThreshold)
    {
        if (!filter_var($maxThreshold, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_lt] must be the valid integer.");
        }

        $this->maxThreshold = (int) $maxThreshold;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_numeric($value)) {
            return "The field {$field} must be a valid numeric value.";
        }

        if (varDigits($value) >= $this->maxThreshold) {
            return "The field {$field} must contain less than {$this->maxThreshold} digits.";
        }

        return true;
    }
}
