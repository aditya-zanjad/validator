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
class DateEqual extends AbstractRule
{
    /**
     * @var string $equalDateFormat
     */
    protected string $equalDateFormat;

    /**
     * @var \DateTime $equalDate
     */
    protected DateTime $equalDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $equalDateFormat
     */
    public function __construct(string $equalDateFormat)
    {
        try {
            $this->equalDateFormat  =   $equalDateFormat;
            $this->equalDate        =   new DateTime($equalDateFormat);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The validation rule [date_equal] must be provided with a valid equal date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field :{field} must be a date before or equal to the date {$this->equalDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            if ($givenDateTime != $this->equalDate) {
                return "The field :{field} must be a date before or equal to the date {$this->equalDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            return "The field :{field} must be a date before or equal to the date {$this->equalDateFormat}.";
        }

        return true;
    }
}
