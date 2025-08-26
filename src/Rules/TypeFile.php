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
    public function check(string $field, $value): bool
    {
        return match (\gettype($value)) {
            'string'    =>  $this->validateFromPath($value),
            'array'     =>  $this->validateFromUpload($value),
            'object'    =>  $this->validateFromObject($value),
            'resource'  =>  $this->validateFromResource($value),
            default     =>  false
        };
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid readable file.';
    }

    /**
     * Validate the file if it's path is given.
     *
     * @param string $value
     * 
     * @return bool
     */
    protected function validateFromPath(string $value): bool
    {
        return \is_file($value) && \is_readable($value);
    }

    /**
     * Validate the file from the given uploaded file array data.
     *
     * @param array $value
     * 
     * @return bool
     */
    protected function validateFromUpload(array $value): bool
    {
        return (isset($value['error']) && $value['error'] === UPLOAD_ERR_OK) || (isset($value['tmp_name']) && is_uploaded_file($value['tmp_name']));
    }

    /**
     * Validate the file from the '\SplFileInfo' object.
     *
     * @param object $value
     * 
     * @return bool
     */
    protected function validateFromObject(object $value): bool
    {
        return \extension_loaded('SPL')
            && \class_exists(SplFileInfo::class)
            && $value instanceof SplFileInfo
            && $value->isFile()
            && $value->isReadable();
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
