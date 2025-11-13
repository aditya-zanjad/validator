<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

class Error
{
    protected array $errors = [];

    public function isEmpty(): bool
    {
        return empty($this->errors);
    }

    public function add(string $field, string $message): static
    {
        $this->errors[$field][] = $message;
        return $this;
    }

    public function all(): array
    {
        return $this->errors;
    }

    public function of(string $field): ?array
    {
        return $this->errors[$field] ?? null;
    }

    public function firstOf(string $field): ?string
    {
        if (!isset($this->errors[$field])) {
            return null;
        }

        if (empty($this->errors[$field])) {
            return null;
        }

        return $this->errors[$field][\array_key_first($this->errors[$field])];
    }

    public function lastOf(string $field): ?string
    {
        if (!isset($this->errors[$field])) {
            return null;
        }

        if (empty($this->errors[$field])) {
            return null;
        }

        return $this->errors[$field][\array_key_last($this->errors[$field])];
    }
}
