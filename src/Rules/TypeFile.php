<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeFile extends AbstractRule
{
    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (is_string($value) && file_exists($value)) {
            return true;
        }

        if (!is_resource($value)) {
            return "The field {$field} must be a valid file.";
        }

        $metadata = stream_get_meta_data($value);

        if ($metadata['wrapper_type'] !== 'plainfile') {
            return "The field {$field} must be a valid file resource.";
        }

        return true;
    }
}
