<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use finfo;
use Exception;
use AdityaZanjad\Validator\Enums\MimeType;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Mime extends AbstractRule
{
    /**
     * @var array<int, string> $givenMimeTypes
     */
    protected array $givenMimeTypes;

    /**
     * @param string ...$givenMimeTypes
     */
    public function __construct(string ...$givenMimeTypes)
    {
        if (empty($givenMimeTypes)) {
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] must be provided with at least one parameter.");
        }

        $this->givenMimeTypes = array_map(function ($mime) {
            return trim($mime);
        }, $givenMimeTypes);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        // First, we'll check that the supplied list of MIME types is a valid one.
        $mimeTypes          =   array_unique(MimeType::values());
        $invalidMimeTypes   =   array_diff($this->givenMimeTypes, $mimeTypes);

        if (!empty($invalidMimeTypes)) {
            $invalidMimeTypesImploded = implode(',', $invalidMimeTypes);
            throw new Exception("[Developer][Exception]: The field [$field] has been provided with an invalid list of MIME types: {$invalidMimeTypesImploded}.");
        }

        $valueMimeType = null;

        switch (gettype($value)) {
            case 'string':
                if (is_file($value) && is_readable($value)) {
                    $valueMimeType = mime_content_type($value);
                    break;
                }

                $finfo          =   new finfo();
                $valueMimeType  =   $finfo->buffer($value, FILEINFO_MIME_TYPE);
                break;

            case 'resource':
                $metadata = stream_get_meta_data($value);

                if ($metadata['wrapper_type'] !== 'plainfile') {
                    return "The field {$field} must be a valid file.";
                }

                $valueMimeType = mime_content_type($metadata['uri']);
                break;

            default:
                throw new Exception("[Developer][Exception]: The field :{field} must be a valid file.");
                break;
        }

        if (!in_array($valueMimeType, $this->givenMimeTypes)) {
            $validMimeTypesImploded = implode(', ', $this->givenMimeTypes);
            return "The field [$field] must have one of these MIME types: {$validMimeTypesImploded}";
        }

        return true;
    }
}
