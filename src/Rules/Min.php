<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class Min extends AbstractRule
{
    protected string $error = 'The field :{field} is invalid.';

    public function __construct(protected int|float|string $minValue)
    {
        if (\filter_var($minValue, FILTER_VALIDATE_FLOAT) === false || $minValue < 0) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The parameter supplied to the validation rule min [{$currentClassName}] must be either an INTEGER or a FLOAT value greater than or equal to 0.");
        }
    }

    public function validate(mixed $value): bool
    {
        if (\is_null($value)) {
            return false;
        }

        if (\is_string($value)) {
            // File
            if (\is_file($value)) {
                // TODO => Add logic for calculating file size.
                return false;
            }

            // String
            if (\strlen($value) < $this->minValue) {
                $this->error = "The field :{field} must contain at least {}";
                return false;
            }
        }

        // Integer / Float
        if (\filter_var($value, FILTER_VALIDATE_FLOAT) && $value < $this->minValue) {
            $this->error = "The field :{field} must not be less than {$this->minValue}.";
            return false;
        }

        // Array
        if (\is_array($value) && \count($value) < $this->minValue) {
            $this->error = "The field :{field} must contain at least {$this->minValue} elements.";
            return false;
        }

        if (\is_resource($value)) {
            $meta = stream_get_meta_data($value);

            if (!\in_array('plainfile', [$meta['wrapper_type'], $meta['stream_type']])) {
                $this->error = 'The field :{field} must be a valid file.';
                return false;
            }

            $fileStats = fstat($value);

            // TODO => Add logic for transforming the given file size.
            if ($fileStats['size'] < $this->minValue) {
                $this->error = "The field :{field} must have at least the size {$this->minValue}.";
                return false;
            }
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
