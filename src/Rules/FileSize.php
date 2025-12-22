<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileSize extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $allowedFileSize;

    public function __construct(int|float|string $allowedFileSize)
    {
        $this->error        =   "The file :{field} must be exactly of the size {$allowedFileSize}.";
        $allowedFileSize    =   $this->getFilteredFileSize($allowedFileSize);

        if (\is_null($allowedFileSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule file_size [{$currentClass}] is invalid.");
        }

        $this->allowedFileSize = $allowedFileSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be a non-empty file.';
            return false;
        }

        return $filesize === $this->allowedFileSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
