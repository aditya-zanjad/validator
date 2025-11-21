<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileGreaterThan extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $minInvalidSize;

    public function __construct(int|float|string $minInvalidSize)
    {
        $this->error    =   "The file :{field} must be greater than the size {$minInvalidSize}.";
        $minInvalidSize =   $this->getFilteredFileSize($minInvalidSize);

        if (\is_null($minInvalidSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter min invalid size passed to the validation rule file_min [{$currentClass}] is invalid.");
        }

        $this->minInvalidSize = $minInvalidSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize > $this->minInvalidSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
