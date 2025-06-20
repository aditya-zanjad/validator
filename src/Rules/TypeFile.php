<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use SplFileInfo;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeFile extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $result = null;

        switch (gettype($value)) {
            case 'string':
                $result = $this->validateFromPath($value);
                break;

            case 'array':
                $result = $this->validateFromUpload($value);
                break;

            case 'object':
                $result = $this->validateFromObject($value);
                break;

            case 'resource':
                $result = $this->validateFromResource($value);
                break;

            default:
                // No Action
                break;
        }

        if ($result === false) {
            return 'The field :{field} must be a valid readable file.';
        }

        return true;
    }

    protected function validateFromPath(string $value): bool
    {
        return is_file($value) && is_readable($value);
    }

    protected function validateFromUpload(array $value): bool
    {
        return (isset($value['error']) && $value['error'] === UPLOAD_ERR_OK)
            || (isset($value['tmp_name']) && is_uploaded_file($value['tmp_name']));
    }

    protected function validateFromObject($value): bool
    {
        return class_exists('\\SplFileInfo')
            && $value instanceof SplFileInfo
            && $value->isFile()
            && $value->isReadable();
    }

    protected function validateFromResource($value): bool
    {
        if (get_resource_type($value) !== 'stream') {
            return false;
        }

        $metadata = stream_get_meta_data($value);
        return isset($metadata['wrapper_type']) && $metadata['wrapper_type'] === 'plainfile';
    }
}
