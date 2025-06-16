<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsGreaterThan extends AbstractRule
{
    /**
     * @var int $minThreshold
     */
    protected int $minThreshold;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $minThreshold
     *
     * @throws \Exception
     */
    public function __construct($minThreshold)
    {
        if (!filter_var($minThreshold, FILTER_VALIDATE_INT)) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_gt] must be a valid integer.");
        }

        $this->minThreshold = (int) $minThreshold;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_numeric($value)) {
            return "The field {$field} must be a valid numeric value.";
        }

        if (varDigits($value) <= $this->minThreshold) {
            return "The field {$field} must contain more than {$this->minThreshold} digits.";
        }

        return true;
    }
}
