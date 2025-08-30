<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class DigitsGt extends AbstractRule
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
        if (\filter_var($minThreshold, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits_gt] must be a valid integer.");
        }

        $this->minThreshold = (int) $minThreshold;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $digits = varDigits($value);
        return !\is_null($digits) && varDigits($value) > $this->minThreshold;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain more than {$this->minThreshold} digits.";
    }
}
