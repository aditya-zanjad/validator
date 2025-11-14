<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class DateGreaterThan extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error;

    protected DateTime $afterDateInstance;

    public function __construct(string $afterDate)
    {
        $afterDateInstance = $this->tryParseDateTime($afterDate);

        if (\is_null($afterDateInstance)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule date_gt [{$currentClassName}] must be a valid date.");
        }

        $this->error                =   "The field :{field} must be a date greater than the date {$afterDate}.";
        $this->afterDateInstance    =   $afterDateInstance;
    }

    public function validate(mixed $value): bool
    {
        $givenDateTimeInstance = $this->tryParseDateTime($value);

        if (\is_null($givenDateTimeInstance)) {
            $this->error = 'The field :{field} must be a valid date.';
            return false;
        }

        if ($givenDateTimeInstance <= $this->afterDateInstance) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
