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
class DateBefore extends AbstractRule
{
    /**
     * @var string $beforeDateFormat
     */
    protected string $beforeDateFormat;

    /**
     * @var \DateTime $beforeDate
     */
    protected DateTime $beforeDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $beforeDateFormat
     */
    public function __construct(string $beforeDateFormat)
    {
        try {
            $this->beforeDateFormat =   $beforeDateFormat;
            $this->beforeDate       =   new DateTime($beforeDateFormat);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The validation rule [date_lt] must be provided with a valid before date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return "The field :{field} must be a valid date before the date {$this->beforeDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            if (!($givenDateTime < $this->beforeDate)) {
                return "The field :{field} must be a valid date before the date {$this->beforeDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            return "The field :{field} must be a valid date before the date {$this->beforeDateFormat}.";
        }

        return true;
    }
}
