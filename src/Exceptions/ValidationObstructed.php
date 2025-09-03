<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Exceptions;

use Exception;

/**
 * @version 2.0
 */
class ValidationObstructed extends Exception
{
    /**
     * To contain a list of validation error messages.
     *
     * @param   string                      $message
     * @param   int                         $code
     */
    public function __construct(string $message = 'Validation Errors', int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
