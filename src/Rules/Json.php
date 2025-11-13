<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

class Json extends AbstractRule
{
    public function validate(mixed $value): bool
    {
        if (\is_string($value) && \is_file($value)) {
            return $this->isJson(\file_get_contents($value));
        }

        if (\is_resource($value)) {
            $meta = stream_get_meta_data($value);

            if (\in_array('plainfile', [$meta['wrapper_type'], $meta['stream_type']])) {
                return $this->isJson(\stream_get_contents($value, -1, 0));
            }
            
            return false;
        }

        return $this->isJson($value);
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid JSON.';
    }

    protected function isJson(mixed $content)
    {
        if (\function_exists('\\json_validate')) {
            return \json_validate($content);
        }

        \json_decode($content);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
