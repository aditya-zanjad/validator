<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Traits;

trait VarHelpers
{
    protected function calculateVarSize(mixed $var): null|int|float
    {
        if (\is_null($var)) {
            return null;
        }

        if (\is_bool($var)) {
            return (int) $var;
        }

        if (\is_string($var)) {
            if (\is_file($var)) {
                return \filesize($var);
            }
        }

        if (\filter_var($var, FILTER_VALIDATE_FLOAT) !== false) {
            return $var;
        }

        if (\is_array($var)) {
            return \count($var);
        }

        if (\is_resource($var)) {
            $meta = stream_get_meta_data($var);

            if (!\in_array('plainfile', [$meta['wrapper_type'], $meta['stream_type']])) {
                return null;
            }

            $fileStats = fstat($var);
            return $fileStats['size'];
        }

        return null;
    }

    protected function transformFileSize(int|float|string $givenSize): null|int|float
    {
        if (\filter_var($givenSize, FILTER_VALIDATE_FLOAT) !== false) {
            return (float) $givenSize;
        }

        $givenSize      =   \trim($givenSize);
        $sizeUnit       =   \substr($givenSize, -1, 2);
        $numericSize    =   \substr($givenSize, 0, -2);

        return match ($sizeUnit) {
            'KB'    =>  $numericSize * 1024,
            'MB'    =>  $numericSize * 1024 * 1024,
            'GB'    =>  $numericSize * 1024 * 1024 * 1024,
            'TB'    =>  $numericSize * 1024 * 1024 * 1024,
            default =>  null
        };
    }
}
