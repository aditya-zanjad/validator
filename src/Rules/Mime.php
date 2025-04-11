<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use Throwable;
use AdityaZanjad\Validator\Core\Utils\Arr;
use AdityaZanjad\Validator\Enums\MimeType;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\VarHelpers;

/**
 * @version 1.0
 */
class Mime extends AbstractRule
{
    use VarHelpers;

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

        $this->givenMimeTypes = Arr::mapFn($givenMimeTypes, fn ($mime) => trim($mime));
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
            return "The field [$field] has invalid list of MIME types for the rule [mime]: {$invalidMimeTypesImploded}.";
        }

        /**
         * If only a path is given to the file, we'll need to open it obtain
         * important information about it to validate its MIME type.
         */
        $file = null;

        if (is_string($value) && file_exists($value)) {
            try {
                $file = fopen($value, 'r');
            } catch (Throwable $e) {
                // dd($e);
                return "The file [{$field}] must be accessible to figure out its MIME type.";
            }
        }

        // Make sure that the file has been properly loaded.
        if (!is_resource($file)) {
            return "The field {$field} must be a valid file.";
        }

        $metadata       =   stream_get_meta_data($file);
        $valueMimeType  =   mime_content_type($file);

        fclose($file);

        if (!in_array($metadata['wrapper_type'], ['plainfile'])) {
            return "The field {$field} must be a valid file.";
        }

        if (!in_array($valueMimeType, $this->givenMimeTypes)) {
            $validMimeTypesImploded = implode(', ', $this->givenMimeTypes);
            return "The field [$field] must have one of these MIME types: {$validMimeTypesImploded}";
        }

        return true;
    }
}
