<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class Url extends AbstractRule
{
    protected array $validOptions = [
        'path'  =>  'path',
        'query' =>  'query'
    ];

    protected array $suppliedOptions = [];

    protected string $error = 'The field :{field} must be a valid URL.';

    public function __construct(string ...$options)
    {
        if (\count($options) > 2) {
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] accepts only two parameters.");
        }

        $options = \array_values($options);

        if ($options !== \array_unique($options)) {
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] must not be supplied with duplicated parameters.");
        }

        $this->suppliedOptions = $options;

        if (empty($options)) {
            return;
        }

        $invalidOptions = \array_diff($options, $this->validOptions);

        if (!empty($invalidOptions)) {
            $validOptions = \implode(', ', $this->validOptions);
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] accepts only these options: {$validOptions}");
        }
    }

    public function validate(mixed $value): bool
    {
        $options = 0;

        if ($this->shouldValidateUrlPath()) {
            $options |= FILTER_FLAG_PATH_REQUIRED;
        }

        if ($this->shouldValidateUrlQuery()) {
            $options |= FILTER_FLAG_QUERY_REQUIRED;
        }

        return \filter_var($value, FILTER_VALIDATE_URL, $options) !== false;
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid URL.';
    }

    protected function shouldValidateUrlPath(): bool
    {
        return \in_array($this->validOptions['path'], $this->suppliedOptions);
    }

    protected function shouldValidateUrlQuery(): bool
    {
        return \in_array($this->validOptions['query'], $this->suppliedOptions);
    }
}
