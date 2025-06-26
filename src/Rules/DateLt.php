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
class DateLt extends AbstractRule
{
    /**
     * @var string $ltDateString
     */
    protected string $ltDateString;

    /**
     * @var bool|\DateTime $ltDate
     */
    protected $ltDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $ltDateString
     */
    public function __construct(string $ltDateString)
    {
        $this->ltDateString =   $ltDateString;
        $this->ltDate       =   parseDateTime($ltDateString);

        if ($this->ltDate === false) {
            throw new Exception("[Developer][Exception]: The validation rule [date_lt] must be provided with a valid before date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $givenDateTime = parseDateTime($value);

        if ($givenDateTime === false || $givenDateTime >= $this->ltDate) {
            return "The field :{field} must be a valid date before the date {$this->ltDateString}.";
        }

        return true;
    }
}
