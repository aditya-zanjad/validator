<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class DateLte extends AbstractRule
{
    /**
     * @var string $lteDateString
     */
    protected string $lteDateString;

    /**
     * @var \DateTime $lteDate
     */
    protected DateTime $lteDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $lteDateString
     */
    public function __construct(string $lteDateString)
    {
        $this->lteDateString    =   $lteDateString;
        $this->lteDate          =   parseDateTime($lteDateString);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $givenDateTime = parseDateTime($value);
        return $givenDateTime !== false && $givenDateTime <= $this->lteDate;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a date less than or equal to: {$this->lteDateString}";
    }
}
