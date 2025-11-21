<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\FileHelpers;

class FileFilled extends AbstractRule
{
    use FileHelpers;

    protected string $error = 'The field :{field} invalid.';

    public function validate(mixed $value): bool
    {
        $filesize = $this->getFileSize($value);

        if (\is_null($filesize)) {
            $this->error = 'The field :{field} must not be an empty file.';
            return false;
        }

        $this->error = 'The field :{field} must not be an empty file.';
        return $filesize > 0;
    }

    public function error(): string
    {
        return $this->error;
    }
}