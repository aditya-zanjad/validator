<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeJson extends AbstractRule
{
    /**
     * To determine the depth to go in the JSON data to validate it.
     *
     * @var int $jsonDepth
     */
    protected int $jsonDepth;

    /**
     * Inject necessary dependencies into the class.
     *
     * @param string $jsonDepth
     */
    public function __construct(string $jsonDepth = '1024')
    {
        $this->jsonDepth = (int) $jsonDepth;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $preprocessedValue = $this->preprocessValue($value);

        if ($preprocessedValue === false) {
            return false;
        }

        return \function_exists('\\json_validate') && \json_validate($preprocessedValue, $this->jsonDepth)
            || \json_decode($preprocessedValue, true, $this->jsonDepth) && \json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid JSON.';
    }

    /**
     * Preprocess the given value before actually performing a validation on it.
     *
     * @param mixed $value
     * 
     * @return bool|string
     */
    protected function preprocessValue($value)
    {
        switch (gettype($value)) {
            // If a path towards a JSON file is given.
            case 'string':
                if (is_string($value) && is_file($value)) {
                    $value = file_get_contents($value);
                }
                break;

            // If a JSON resource/stream is given.
            case 'resource':
                if (is_resource($value)) {
                    $metadata = stream_get_meta_data($value);

                    if ($metadata['wrapper_type'] !== 'plainfile') {
                        return false;
                    }

                    $value = stream_get_contents($value, -1, 0);
                }
                break;

            default:
                $value = false;
        }

        return $value;
    }
}
