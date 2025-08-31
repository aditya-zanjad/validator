<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use SplFileInfo;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Mime extends AbstractRule
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @var array<int, string> $validMimes
     */
    protected array $validMimes;

    /**
     * @param string ...$validMimes
     */
    public function __construct(string ...$validMimes)
    {
        if (empty($validMimes)) {
            throw new Exception("[Developer][Exception]: The validation rule [mime] must be provided with at least one parameter.");
        }

        $this->validMimes = \array_map(fn($mime) => \strtolower(\trim($mime)), $validMimes);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $mime = match (\gettype($value)) {
            'string'    =>  $this->obtainMimeFromString($value),
            'array'     =>  $this->obtainMimeFromArray($value),
            'resource'  =>  $this->obtainMimeFromResource($value),
            'object'    =>  $this->obtainMimeFromObject($value)
        };

        if (!\in_array($mime, $this->validMimes)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        $joinedValues = \array_reduce($this->validMimes, fn($carry, $mime) => "{$carry}\"{$mime}\", ");
        $joinedValues = \rtrim($joinedValues, ', ');

        return "The field :{field} must match one of these MIME types: {$joinedValues}";
    }

    /**
     * Attempt to obtain the MIME type of the file from its given path in the string format.
     *
     * @param string $value
     * 
     * @return null|string
     */
    protected function obtainMimeFromString(string $value): ?string
    {
        if (!\is_file($value)) {
            return null;
        }

        if (\extension_loaded('fileinfo')) {
            return \finfo_file(\finfo_open(FILEINFO_MIME_TYPE), $value);
        }

        return \mime_content_type($value);
    }

    /**
     * Attempt to obtain the MIME type of an uploaded file from its given "$_FILES" array structure.
     *
     * @param array $value
     * 
     * @return null|string
     */
    protected function obtainMimeFromArray(array $value): ?string
    {
        if (isset($value['error']) && $value['error'] === UPLOAD_ERR_OK && isset($value['tmp_name']) && is_uploaded_file($value['tmp_name'])) {
            return $this->obtainMimeFromString($value['tmp_name']);
        }

        return null;
    }

    /**
     * Attempt to obtain the MIME type of the given file resource.
     *
     * @param mixed $value
     * 
     * @return null|string
     */
    protected function obtainMimeFromResource(mixed $value): ?string
    {
        $metadata = \stream_get_meta_data($value);

        if ($metadata['wrapper_type'] !== 'plainfile') {
            return null;
        }

        return $this->obtainMimeFromString($metadata['uri']);
    }

    /**
     * Attempt to obtain the MIME type of the given object.
     *
     * @param object $value
     * 
     * @return null|string
     */
    protected function obtainMimeFromObject(object $value): ?string
    {
        if (!\extension_loaded('SPL') || !$value instanceof SplFileInfo) {
            return null;
        }

        return $this->obtainMimeFromString($value->getPathname());
    }
}
