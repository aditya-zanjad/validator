<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileBetween extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    protected int|float $minInvalidSize;

    protected int|float $maxInvalidSize;

    public function __construct(int|float|string $minInvalidSize, int|float|string $maxInvalidSize)
    {
        $this->error    =   "The file :{field} size must be in the range [{$minInvalidSize} - {$maxInvalidSize}].";
        $minInvalidSize =   $this->getFilteredFileSize($minInvalidSize);
        $maxInvalidSize =   $this->getFilteredFileSize($maxInvalidSize);

        if (\is_null($minInvalidSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter min required size passed to the validation rule file_range [{$currentClass}] is invalid.");
        }

        if (\is_null($maxInvalidSize)) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max allowed size passed to the validation rule file_range [{$currentClass}] is invalid.");
        }

        if ($maxInvalidSize < $minInvalidSize) {
            $currentClass = static::class;
            throw new Exception("[Developer][Exception]: The parameter max allowed size must not be less than the parameter min allowed size passed to the validation rule file_range [{$currentClass}].");
        }

        $this->maxInvalidSize   =   $maxInvalidSize;
        $this->minInvalidSize   =   $minInvalidSize;
    }

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        return $filesize > $this->minInvalidSize && $filesize < $this->maxInvalidSize;
    }

    public function error(): string
    {
        return $this->error;
    }
}
