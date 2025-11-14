<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class DateLessThan extends AbstractRule
{
    use DateTimeHelpers;

    protected DateTime $beforeDateInstance;

    protected string $error;

    public function __construct(string $beforeDate)
    {
        $beforeDateInstance = $this->tryParseDateTime($beforeDate);

        if (\is_null($beforeDateInstance)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule date_lt [{$currentClassName}] must be a valid date.");
        }

        $this->error                =   "The field :{field} must be a date less than the date {$beforeDate}.";
        $this->beforeDateInstance   =   $beforeDateInstance;
    }

    public function validate(mixed $value): bool
    {
        $givenDateTimeInstance = $this->tryParseDateTime($value);

        if (\is_null($givenDateTimeInstance)) {
            $this->error = 'The field :{field} must be a valid date.';
            return false;
        }

        if ($givenDateTimeInstance >= $this->beforeDateInstance) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}