<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileRange extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $minRequiredSize;

    protected int|float $maxAllowedSize;


    public function __construct(int|float|string $minRequiredSize, int|float|string $maxAllowedSize)
    {
        $this->error        =   "The file :{field} size must be in the range [{$minRequiredSize} - {$maxAllowedSize}].";
        $minRequiredSize    =   $this->getFilteredFileSize($minRequiredSize);
        $maxAllowedSize     =   $this->getFilteredFileSize($maxAllowedSize);

        if (\is_null($minRequiredSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter min required size passed to the validation rule file_range [{$currentClass}] is invalid.");
        }

        if (\is_null($maxAllowedSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max allowed size passed to the validation rule file_range [{$currentClass}] is invalid.");
        }

        if ($maxAllowedSize < $minRequiredSize) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max allowed size must not be less than the parameter min allowed size passed to the validation rule file_range [{$currentClass}].");
        }

        $this->minRequiredSize  =   $minRequiredSize;
        $this->maxAllowedSize   =   $maxAllowedSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize >= $this->minRequiredSize && $filesize <= $this->maxAllowedSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
