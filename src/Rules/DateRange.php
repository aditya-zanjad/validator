<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class DateRange extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error;

    protected DateTime $minDateInstance;

    protected DateTime $maxDateInstance;

    public function __construct(protected string $minDate, protected string $maxDate)
    {
        $minDateInstance = $this->tryParseDateTime($minDate);
        $maxDateInstance = $this->tryParseDateTime($maxDate);

        if (\is_null($minDateInstance) || \is_null($maxDateInstance)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameters supplied to the validation rule date_range [{$currentClassName}] must be valid dates.");
        }

        $this->error            =   "The field :{field} must be in the date range [{$minDate} - {$maxDate}].";
        $this->minDateInstance  =   $minDateInstance;
    }

    public function validate(mixed $value): bool
    {
        $givenDateTimeInstance = $this->tryParseDateTime($value);

        if (\is_null($givenDateTimeInstance)) {
            $this->error = 'The field :{field} must be a valid date.';
            return false;
        }

        if ($givenDateTimeInstance < $this->minDateInstance) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
