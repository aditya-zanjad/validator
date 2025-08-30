<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class DateBetween extends AbstractRule
{
    /**
     * @var string $minDateString
     */
    protected string $minDateString;

    /**
     * @var string $maxDateString
     */
    protected string $maxDateString;

    /**
     * @var bool|\DateTime $minDate
     */
    protected $minDate;

    /**
     * @var bool|\DateTime $maxDate
     */
    protected $maxDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param   string  $minDate
     * @param   string  $maxDate
     */
    public function __construct(string $minDate, string $maxDate)
    {
        // Parse minimum date
        $this->minDateString    =   $minDate;
        $this->minDate          =   parseDateTime($minDate);

        if ($this->minDate === false) {
            throw new Exception("[Developer][Exception]: The validation rule [date_between] requires a valid minimum date.");
        }

        // Parse maximum date
        $this->maxDateString    =   $maxDate;
        $this->maxDate          =   parseDateTime($maxDate);

        if ($this->minDate === false) {
            throw new Exception("[Developer][Exception]: The validation rule [date_between] requires a valid maximum date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $givenDateTime = parseDateTime($value);

        return $givenDateTime !== false 
            && $givenDateTime >= $this->minDate 
            && $givenDateTime <= $this->maxDate;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a date between the dates {$this->minDateString} and {$this->maxDateString}.";
    }
}
