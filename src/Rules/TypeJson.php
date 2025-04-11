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
    public function check(string $field, mixed $value): bool|string
    {
        if (function_exists('json_validate') && json_validate($value, $this->jsonDepth)) {
            return true;
        }

        if (json_decode($value, true, $this->jsonDepth) && json_last_error() === JSON_ERROR_NONE) {
            return true;
        }

        return "The field {$field} must be a valid JSON.";
    }
}
