<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class File extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        if (\is_string($value)) {
            return \is_file($value);
        }

        if (\is_resource($value)) {
            $meta = \stream_get_meta_data($value);
            return \in_array('plainfile', [$meta['wrapper_type'], $meta['stream_type']]);
        }

        if ($value instanceof \SplFileInfo) {
            return $value->isFile();
        }

        if (\is_array($value)) {
            return isset($value['error'])
                && $value['error'] !== UPLOAD_ERR_OK
                && isset($value['tmp_name'])
                && \is_uploaded_file($value['tmp_name']);
        }

        return false;
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid file.';
    }
}
