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
    public function check(mixed $value): bool
    {
        $jsonContents = $this->obtainJsonContents($value);

        if ($jsonContents === false) {
            return false;
        }

        if (\function_exists('\\json_validate')) {
            return \json_validate($jsonContents, $this->jsonDepth);
        }

        \json_decode($jsonContents, true, $this->jsonDepth);
        return \json_last_error() === JSON_ERROR_NONE;
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
    protected function obtainJsonContents($value)
    {
        if (\is_string($value)) {
            if (\is_file($value)) {
                return \file_get_contents($value);
            }

            return $value;
        }

        if (!\is_resource($value)) {
            return false;
        }

        $metadata = stream_get_meta_data($value);

        if ($metadata['wrapper_type'] !== 'plainfile') {
            return false;
        }

        return stream_get_contents($value, -1, 0);
    }
}
