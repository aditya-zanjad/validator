<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use finfo;
use Exception;
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

        $this->givenMimeTypes = \array_map(fn ($mime) => \trim($mime), $givenMimeTypes);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $valueMimeType = null;

        switch (\gettype($value)) {
            case 'string':
                if (\is_file($value) && \is_readable($value)) {
                    $valueMimeType = \mime_content_type($value);
                    break;
                }

                $finfo          =   new finfo();
                $valueMimeType  =   $finfo->buffer($value, FILEINFO_MIME_TYPE);
                break;

            case 'resource':
                $metadata = \stream_get_meta_data($value);

                if ($metadata['wrapper_type'] !== 'plainfile') {
                    $this->message = "The field {$field} must be a valid file.";
                    return false;
                }

                $valueMimeType = \mime_content_type($metadata['uri']);

                if ($valueMimeType === false) {
                    $finfo          =   new finfo();
                    $valueMimeType  =   $finfo->file($metadata['uri'], FILEINFO_MIME_TYPE);
                }
                break;

            default:
                throw new Exception("[Developer][Exception]: The field :{field} must be a valid file.");
                break;
        }

        if (!in_array($valueMimeType, $this->givenMimeTypes)) {
            $this->message = "The field [$field] must have one of these MIME types: " . implode(', ', $this->givenMimeTypes);
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
