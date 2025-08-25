<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Exceptions;

use Exception;

/**
 * @version 2.0
 */
class ValidationFailed extends Exception
{
    /**
     * To contain a list of validation error messages.
     *
     * @var array<int|string, mixed> $errors
     */
    protected array $errors;

    /**
     * @param   string                      $message
     * @param   int                         $code
     * @param   array<int|string, mixed>    $errors
     */
    public function __construct(string $message = 'Validation Errors', array $errors = [], int $code = 422)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    /**
     * Get the list of all the validation errors that've occurred.
     *
     * @return array<int|string, mixed>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
