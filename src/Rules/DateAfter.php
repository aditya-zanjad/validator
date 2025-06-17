<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use DateMalformedStringException;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class DateAfter extends AbstractRule
{
    /**
     * @var string $afterDateFormat
     */
    protected string $afterDateFormat;

    /**
     * @var \DateTime $afterDate
     */
    protected DateTime $afterDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $afterDateFormat
     */
    public function __construct(string $afterDateFormat)
    {
        try {
            $this->afterDateFormat  =   $afterDateFormat;
            $this->afterDate        =   new DateTime($afterDateFormat);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The validation rule [date_gt] must be provided with a valid after date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field :{field} must be a valid date after the date {$this->afterDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            if (!($givenDateTime < $this->afterDate)) {
                return "The field :{field} must be a valid date after the date {$this->afterDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            return "The field :{field} must be a valid date after the date {$this->afterDateFormat}.";
        }

        return true;
    }
}
