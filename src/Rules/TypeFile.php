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
    public function check(string $field, mixed $value): bool
    {
        return match (\gettype($value)) {
            'string'    =>  \is_file($value),
            'array'     =>  (isset($value['error']) && $value['error'] === UPLOAD_ERR_OK) || (isset($value['tmp_name']) && is_uploaded_file($value['tmp_name'])),
            'object'    =>  \extension_loaded('SPL') && \class_exists(SplFileInfo::class) && $value instanceof SplFileInfo && $value->isFile(),
            'resource'  =>  $this->validateFromResource($value),
            default     =>  false
        };
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid file.';
    }

    /**
     * Validate the file from the given file resource.
     *
     * @param mixed $value
     * 
     * @return bool
     */
    protected function validateFromResource($value): bool
    {
        if (get_resource_type($value) !== 'stream') {
            return false;
        }

        $metadata = stream_get_meta_data($value);
        return $metadata['wrapper_type'] === 'plainfile';
    }
}
