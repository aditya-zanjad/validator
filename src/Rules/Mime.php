<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

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

        $this->validMimes = \array_map(fn($mime) => \trim($mime), $validMimes);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $mime = null;

        switch (\gettype($value)) {
            case 'string':
                if (!\is_file($value)) {
                    return false;
                }

                $mime = \extension_loaded('fileinfo')
                    ? \finfo_file(\finfo_open(FILEINFO_MIME_TYPE), $value)
                    : \mime_content_type($value);
                break;

            case 'resource':
                $metadata = \stream_get_meta_data($value);

                if ($metadata['wrapper_type'] !== 'plainfile') {
                    return false;
                }

                $mime = \extension_loaded('fileinfo')
                    ? \finfo_file(\finfo_open(FILEINFO_MIME_TYPE), $metadata['uri'])
                    : \mime_content_type($metadata['uri']);
                break;

            default:
                return false;
        }

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
        return "The file :{field} must match one of these MIME types: " . implode(', ', $this->validMimes);
    }
}
