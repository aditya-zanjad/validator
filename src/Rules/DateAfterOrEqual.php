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
class DateAfterOrEqual extends AbstractRule
{
    /**
     * @var string $afterOrEqualDateFormat
     */
    protected string $afterOrEqualDateFormat;

    /**
     * @var \DateTime $afterOrEqualDate
     */
    protected DateTime $afterOrEqualDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $afterOrEqualDateFormat
     */
    public function __construct(string $afterOrEqualDateFormat)
    {
        try {
            $this->afterOrEqualDateFormat   =   $afterOrEqualDateFormat;
            $this->afterOrEqualDate         =   new DateTime($afterOrEqualDateFormat);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The validation rule [date_gte] must be provided with a valid after date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field :{field} must be a date after or equal the date {$this->afterOrEqualDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            if ($givenDateTime < $this->afterOrEqualDate) {
                return "The field :{field} must be a date after or equal the date {$this->afterOrEqualDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e);
            // exit;
            return "The field :{field} must be a date after or equal the date {$this->afterOrEqualDateFormat}.";
        }

        return true;
    }
}
