<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class DateMin extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error;

    protected DateTime $minDateInstance;

    public function __construct(string $afterDate)
    {
        $minDateInstance = $this->tryParseDateTime($afterDate);

        if (\is_null($minDateInstance)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule date_min [{$currentClassName}] must be a valid date.");
        }

        $this->error            =   "The field :{field} must not be less than than the date {$afterDate}.";
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
