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

        if (!empty($options) && !empty(\array_diff($options, $this->validOptions))) {
            $validOptions = \implode(', ', $this->validOptions);
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] accepts only these options: {$validOptions}");
        }

        $this->suppliedOptions = $options;
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        if (empty(\trim($value))) {
            return false;
        }

        if (empty($this->suppliedOptions)) {
            return \filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        if ($this->isUrlPathValidIfProvided($value)) {
            return false;
        }

        if ($this->isUrlQueryValidIfProvided($value)) {
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid URL.';
    }

    protected function isUrlPathValidIfProvided(string $url): bool
    {
        return \in_array($this->validOptions['path'], $this->suppliedOptions) && \filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false;
    }

    protected function isUrlQueryValidIfProvided(string $url): bool
    {
        return \in_array($this->validOptions['query'], $this->suppliedOptions) && \filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false;
    }
}
