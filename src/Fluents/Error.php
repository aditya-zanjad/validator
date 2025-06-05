<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Fluents;

use Exception;

use function AdityaZanjad\Validator\Utils\arr_first;

/**
 * @version 1.0
 */
class Error
{
    /**
     * To hold the validation errors.
     *
     * @var array<string, array<int, string>> $errors
     */
    protected array $errors;

    /**
     * Initialize necessary parameters.
     */
    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * Check if the validation errors array is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->errors);
    }

    /**
     * Add a new error message for the given field
     *
     * @param   string      $field
     * @param   bool|string $message
     *
     * @return  static
     */
    public function add(string $field, $message = false): static
    {
        if ($message === false) {
            $message = 'The field :{field} is invalid.';
        }

        if (!is_string($message)) {
            throw new Exception("[Developer][Exception]: The validation error message must be either a STRING or a BOOLEAN value.");
        }

        $this->errors[$field][] = str_replace(':{field}', $field, $message);
        return $this;
    }

    /**
     * Get all of the validation errors.
     *
     * @return array<string, array<int, string>>
     */
    public function all(): array
    {
        return $this->errors;
    }

    /**
     * Get all of the validation errors for a particular field.
     *
     * @param string $field
     *
     * @return null|string|array
     */
    public function allOf(string $field)
    {
        if (!isset($this->errors[$field])) {
            return null;
        }

        return $this->errors[$field];
    }

    /**
     * Get the first validation error message from the errors array.
     *
     * @return null|string
     */
    public function first()
    {
        $firstFieldErrors = arr_first($this->errors);

        if (is_null($firstFieldErrors)) {
            return null;
        }

        return arr_first($firstFieldErrors);
    }

    /**
     * Get the first validation error message of a particular field from the errors array.
     *
     * @return null|string
     */
    public function firstOf(string $field)
    {
        if (!isset($this->errors[$field])) {
            return null;
        }

        return arr_first($this->errors[$field]);
    }
}
