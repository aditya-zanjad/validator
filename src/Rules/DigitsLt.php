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
        if (filter_var($maxThreshold, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_lt] must be a valid integer.");
        }

        if ($maxThreshold < 2) {
            throw new Exception("[Developer][Exception]: The value of the parameter passed to the validation rule [digits_lt] must not be less than 2.");
        }

        $this->maxThreshold = (int) $maxThreshold;
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $digits = varDigits($value);

        if (\is_null($digits)) {
            return false;
        }

        return $digits < $this->maxThreshold;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain less than {$this->maxThreshold} digits.";
    }
}
