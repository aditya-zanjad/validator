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
interface InputManagerInterface
{
    /**
     * Check if the input array is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Get all of the provided input data.
     *
     * @return array<int|string, mixed>
     */
    public function all(): array;

    /**
     * Get all of the array paths in the dot notation form.
     *
     * @return array<int, string>
     */
    public function paths(): array;

    /**
     * Get the value of a particular dot notation array path.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function get(string $path): mixed;

    /**
     * Check if a particular dot notation array path exists in the given array.
     *
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * Check if the value of the given input path is equal to NULL.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function isNull(string $path): bool;

    /**
     * Check if a particular dot notation array path exists & its value is not empty.
     *
     * @param string $path
     *
     * @return bool
     */
    public function filled(string $path): bool;
}
