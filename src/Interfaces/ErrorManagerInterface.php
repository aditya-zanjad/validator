<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Interfaces;

/**
 * The interface that should be implemented by the mandatory rules.
 *
 * The mandatory rules will always be executed regardless of whether the 
 * given input field is present or not OR is equal to NULL or not in 
 * the given input data.
 *
 * @version 1.0
 */
interface ErrorManagerInterface
{
    /**
     * Check if the validation errors array is empty or not.
     *
     * @return bool
     */
    public function empty(): bool;

    /**
     * Add a new error message for the given field
     *
     * @param   string  $field
     * @param   string  $message
     *
     * @return  static
     */
    public function add(string $field, string $message): static;

    /**
     * Get all of the validation errors.
     *
     * @return array<string, array<int, string>>
     */
    public function all(): array;

    /**
     * Get all of the validation errors for a particular field.
     *
     * @param string $field
     *
     * @return null|string|array
     */
    public function of(string $field);

    /**
     * Get the first validation error message from the errors array.
     *
     * @return null|string
     */
    public function first();

    /**
     * Get the first validation error message of a particular field from the errors array.
     *
     * @return null|string
     */
    public function firstOf(string $field);
}
