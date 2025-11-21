<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileLessThan extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $maxInvalidSize;

    public function __construct(int|float|string $maxInvalidSize)
    {
        $this->error    =   "The file :{field} must be greater than the size {$maxInvalidSize}.";
        $maxInvalidSize =   $this->getFilteredFileSize($maxInvalidSize);

        if (\is_null($maxInvalidSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max invalid size passed to the validation rule file_min [{$currentClass}] is invalid.");
        }

        $this->maxInvalidSize = $maxInvalidSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize < $this->maxInvalidSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
