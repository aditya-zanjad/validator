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
     */
    public function __construct(string $comparingDateString)
    {
        $this->comparingDateString  =   $comparingDateString;
        $this->comparingDate        =   parseDateTime($comparingDateString);
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $givenDateTime = parseDateTime($value);
        return $givenDateTime !== false && $givenDateTime > $this->comparingDate;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a valid date greater than: {$this->comparingDateString}";
    }
}
