<?php

namespace AdityaZanjad\Validator\Exception;

use Exception;

class ValidationFailed extends Exception
{
    /**
     * An array containing the validation errors
     *
     * @var array<string, array>
     */
    protected array $errors;

    /**
     * @param   string                  $message
     * @param   array<string, array>    $errors
     */
    public function __construct(string $message = 'Validation Error(s)', array $errors = [])
    {
        parent::__construct($message, 422);
    }

    /**
     * Get all of the validation errors.
     *
     * @return array<string, array>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}