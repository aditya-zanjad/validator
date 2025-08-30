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
class DateEqual extends AbstractRule
{
    /**
     * @var string $comparingDateString
     */
    protected string $comparingDateString;

    /**
     * @var bool|\DateTime $comparingDate
     */
    protected $comparingDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $comparingDateString
     * 
     * @throws \Exception
     */
    public function __construct(string $comparingDateString)
    {
        $this->comparingDateString  =   $comparingDateString;
        $this->comparingDate        =   parseDateTime($comparingDateString);

        if ($this->comparingDate === false) {
            throw new Exception("[Developer][Exception]: The validation rule [date_equal] must be provided with a valid equal date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $givenDateTime = parseDateTime($value);
        return $givenDateTime !== false && $givenDateTime == $this->comparingDate;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a date equal to the date {$this->comparingDateString}.";
    }
}
