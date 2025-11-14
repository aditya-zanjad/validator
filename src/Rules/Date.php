<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class Date extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error = 'The field :{field} must be a valid date.';

    public function __construct(protected string $format = '')
    {
        //
    }

    public function validate(mixed $value): bool
    {
        if (empty($this->format)) {
            $this->error = "The field :{field} must be a valid date with the format {$this->format}";
            return $this->isDateTimeWithFormatParsable($value, $this->format);
        }

        return $this->IsValidDateTime($value);
    }

    public function error(): string
    {
        return $this->error;
    }
}