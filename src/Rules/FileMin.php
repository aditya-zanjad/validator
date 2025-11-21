<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;
use Exception;

class FileMin extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $minRequiredSize;

    public function __construct(int|float|string $minRequiredSize)
    {
        $this->error        =   "The file :{field} must be of at least the size {$minRequiredSize}.";
        $minRequiredSize    =   $this->getFilteredFileSize($minRequiredSize);

        if (\is_null($minRequiredSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter min required size passed to the validation rule file_min [{$currentClass}] is invalid.");
        }

        $this->minRequiredSize = $minRequiredSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize >= $this->minRequiredSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
