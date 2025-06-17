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
class DateBeforeOrEqual extends AbstractRule
{
    /**
     * @var string $beforeOrEqualDateFormat
     */
    protected string $beforeOrEqualDateFormat;

    /**
     * @var \DateTime $beforeOrEqualDate
     */
    protected DateTime $beforeOrEqualDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $beforeOrEqualDateFormat
     */
    public function __construct(string $beforeOrEqualDateFormat)
    {
        try {
            $this->beforeOrEqualDateFormat  =   $beforeOrEqualDateFormat;
            $this->beforeOrEqualDate        =   new DateTime($beforeOrEqualDateFormat);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The validation rule [date_lte] must be provided with a valid before/equal date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return "The field :{field} must be a date before or equal to the date {$this->beforeOrEqualDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            if ($givenDateTime < $this->beforeOrEqualDate) {
                return "The field :{field} must be a date before or equal to the date {$this->beforeOrEqualDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            return "The field :{field} must be a date before or equal to the date {$this->beforeOrEqualDateFormat}.";
        }

        return true;
    }
}
