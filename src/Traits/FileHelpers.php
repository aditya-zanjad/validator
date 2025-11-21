<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Traits;

trait FileHelpers
{
    protected function getFileSize(mixed $value): null|int|float
    {
        if (\is_string($value)) {
            if (!\is_file($value)) {
                $this->error = 'The field :{field} must be a valid file.';
                return null;
            }

            return \filesize($value);
        }

        if (\is_resource($value)) {
            $meta = \stream_get_meta_data($value);

            if (!\in_array('plainfile', [$meta['wrapper_type'], $meta['stream_type']])) {
                $this->error = 'The field :{field} must be a valid file.';
                return null;
            }

            $fileStats = fstat($value);
            return $fileStats['size'];
        }

        if ($value instanceof \SplFileInfo) {
            if (!$value->isFile()) {
                $this->error = 'The field :{field} must be a valid file.';
                return null;
            }

            return $value->getSize();
        }

        if (\is_array($value)) {
            $isValidFile = isset($value['error'])
                && $value['error'] === UPLOAD_ERR_OK
                && isset($value['tmp_name'])
                && \is_uploaded_file($value['tmp_name']);

            if (!$isValidFile) {
                $this->error = 'The field :{field} must be a valid file.';
                return null;
            }

            return $value['size'];
        }

        return null;
    }

    protected function getFilteredFileSize(mixed $size): null|int|float
    {
        if (\filter_var($size, FILTER_VALIDATE_FLOAT) !== false) {
            return (float) $size;
        }

        if (\filter_var($size, FILTER_VALIDATE_INT) !== false) {
            return (int) $size;
        }

        if (!\is_string($size)) {
            return null;
        }

        $size           =   \str_replace(' ', '', $size);
        $sizeUnit       =   \substr($size, -2);
        $sizeUnit       =   \strtoupper($sizeUnit);
        $sizeInNumeric  =   \substr($size, 0, \strlen($size) - 2);

        if (\filter_var($sizeInNumeric, FILTER_VALIDATE_FLOAT) === false && \filter_var($sizeInNumeric, FILTER_VALIDATE_INT) === false) {
            return null;
        }

        $sizeInNumeric = (float) $sizeInNumeric;

        return (int) match ($sizeUnit) {
            'KB'    =>  $sizeInNumeric * 1024,
            'MB'    =>  $sizeInNumeric * 1024 * 1024,
            'GB'    =>  $sizeInNumeric * 1024 * 1024 * 1024,
            'TB'    =>  $sizeInNumeric * 1024 * 1024 * 1024 * 1024,
            default =>  null
        };
    }
}
