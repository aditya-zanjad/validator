<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\DateTimeHelpers;

class Date extends AbstractRule
{
    use DateTimeHelpers;

    protected string $error;

    public function __construct(protected string $format = '')
    {
        //
    }

    public function validate(mixed $value): bool
    {
        return empty($this->format)
            ? $this->IsValidDateTime($value)
            : $this->isDateTimeWithFormatParsable($value, $this->format);
    }

    public function error(): string
    {
        return empty($this->format)
            ? 'The field :{field} must be a valid date.'
            : 'The field :{field} must be a valid date with the format {$this->format}';
    }
}
