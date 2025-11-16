<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class DateBetween extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error;

    protected DateTime $minInvalidDate;

    protected DateTime $maxInvalidDate;

    public function __construct(string $minInvalidDate, string $maxInvalidDate)
    {
        $minInvalidDate = $this->tryParseDateTime($minInvalidDate);
        $maxInvalidDate = $this->tryParseDateTime($maxInvalidDate);

        if (\is_null($minInvalidDate) || \is_null($maxInvalidDate)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameters supplied to the validation rule date_range [{$currentClassName}] must be valid dates.");
        }

        if ($maxInvalidDate <= $minInvalidDate) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter min date passed to the validation rule date_between [{$currentClassName}] must not be greater than the parameter max date.");
        }

        $this->error            =   "The field :{field} must be a date between the dates [{$minInvalidDate} - {$maxInvalidDate}].";
        $this->minInvalidDate  =   $minInvalidDate;
    }

    public function validate(mixed $value): bool
    {
        $givenDateTimeInstance = $this->tryParseDateTime($value);

        if (\is_null($givenDateTimeInstance)) {
            $this->error = 'The field :{field} must be a valid date.';
            return false;
        }

        if ($givenDateTimeInstance <= $this->minInvalidDate || $givenDateTimeInstance >= $this->maxInvalidDate) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
