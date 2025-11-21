<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileMax extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $maxAllowedSize;

    public function __construct(int|float|string $maxAllowedSize)
    {
        $this->error        =   "The file :{field} must not be greater than {$maxAllowedSize}.";
        $maxAllowedSize    =   $this->getFilteredFileSize($maxAllowedSize);

        if (\is_null($maxAllowedSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max allowed size passed to the validation rule file_min [{$currentClass}] is invalid.");
        }

        $this->maxAllowedSize = $maxAllowedSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize <= $this->maxAllowedSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
