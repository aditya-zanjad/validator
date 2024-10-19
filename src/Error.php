<?php

namespace AdityaZanjad\Validator;

use function AdityaZanjad\Validator\Utils\array_value_first;

class Error
{
    /**
     * To contain the validation errors.
     *
     * @var array<string, mixed> $errors
     */
    protected array $errors = [];

    /**
     * Add a new validation error to the errors array.
     *
     * @param   string  $attribute
     * @param   string  $error
     *
     * @return  void
     */
    public function add(string $attribute, bool|string $error): void
    {
        $error                      =   $error ?: 'The attribute :{attribute} is invalid.';
        $error                      =   str_replace(':{attribute}', $attribute, $error);
        $this->errors[$attribute][] =   $error;
    }

    /**
     * Get the first error message of the first field from the errors array.
     *
     * @return mixed
     */
    public function first(): mixed
    {
        $firstField = array_value_first($this->errors);

        if (is_null($firstField)) {
            return null;
        }

        return array_value_first($firstField);
    }

    /**
     * Get the first error message for the given field from the errors array.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function firstOf(string $key): mixed
    {
        if (!array_key_exists($key, $this->errors)) {
            return null;
        }

        return array_value_first($this->errors[$key]);
    }

    /**
     * Return all of the validation errors at once as an array.
     *
     * @return array<string, array>
     */
    public function all(): array
    {
        return $this->errors;
    }

    /**
     * Check if the errors array is empty or not. Useful for checking if any validation error has occurred yet.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->errors);
    }
}