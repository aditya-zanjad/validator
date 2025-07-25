<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsLt extends AbstractRule
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
    public function check(string $field, $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false || filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        if (varDigits($value) >= $this->maxThreshold) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain less than {$this->maxThreshold} digits.";
    }
}
