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
class DateBetween extends AbstractRule
{
    /**
     * @var string $minDateFormat
     */
    protected string $minDateFormat;

    /**
     * @var string $maxDateFormat
     */
    protected string $maxDateFormat;

    /**
     * @var \DateTime $minDate
     */
    protected DateTime $minDate;

    /**
     * @var \DateTime $maxDate
     */
    protected DateTime $maxDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param   string  $minDate
     * @param   string  $maxDate
     */
    public function __construct(string $minDate, string $maxDate)
    {
        try {
            // Parse minimum date
            $this->minDateFormat    =   $minDate;
            $this->minDate          =   new DateTime($minDate);

            // Parse maximum date
            $this->maxDateFormat    =   $maxDate;
            $this->maxDate          =   new DateTime($maxDate);
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [date_between] must be the valid dates.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!is_string($value)) {
            return "The field :{field} must be a date between the dates {$this->minDateFormat} and {$this->maxDateFormat}.";
        }

        try {
            $givenDateTime = new DateTime($value);

            // Make sure that the given date is not less than the minimum date or greater than the maximum date.
            if ($givenDateTime < $this->minDate || $givenDateTime > $this->maxDate) {
                return "The field :{field} must be a date between the dates {$this->minDateFormat} and {$this->maxDateFormat}.";
            }
        } catch (DateMalformedStringException $e) {
            // var_dump($e); exit;
            return "The field :{field} must be a date between the dates {$this->minDateFormat} and {$this->maxDateFormat}.";
        }

        return true;
    }
}
