<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class DateGt extends AbstractRule
{
    /**
     * @var string $gtDateString
     */
    protected string $gtDateString;

    /**
     * @var bool|\DateTime $gtDate
     */
    protected $gtDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $gtDateString
     */
    public function __construct(string $gtDateString)
    {
        $this->gtDateString =   $gtDateString;
        $this->gtDate       =   parseDateTime($gtDateString);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $givenDateTime = parseDateTime($value);

        if ($givenDateTime === false || $givenDateTime <= $this->gtDate) {
            return "The field :{field} must be a valid date greater than: {$this->gtDateString}";
        }

        return true;
    }
}
