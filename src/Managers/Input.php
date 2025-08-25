<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Managers;


use function AdityaZanjad\Validator\Utils\arr_get;
use function AdityaZanjad\Validator\Utils\arr_exists;
use function AdityaZanjad\Validator\Utils\arr_filled;
use function AdityaZanjad\Validator\Utils\arr_nulled;
use function AdityaZanjad\Validator\Utils\arr_dot_paths;
use AdityaZanjad\Validator\Interfaces\InputManagerInterface;

/**
 * @version 1.0
 */
class Input implements InputManagerInterface
{
    /**
     * The provided input data.
     *
     * @var array<int|string, mixed> $data
     */
    protected array $data;

    /**
     * To hold the dot-notation input field paths for repeated usage.
     *
     * @var array<int, string> $paths
     */
    protected array $paths;

    /**
     * Inject and initialize the necessary data.
     *
     * @param array<int|string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Check if the input array is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Get all of the provided input data.
     *
     * @return array<int|string, mixed>
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get all of the array paths in the dot notation form.
     *
     * @return array<int, int|string>
     */
    public function paths(): array
    {
        return $this->paths ??= arr_dot_paths($this->data);
    }

    /**
     * Get only the top-level keys of the array.
     *
     * @return array<int, int|string>
     */
    public function keys(): array
    {
        return \array_keys($this->data);
    }

    /**
     * Get a value of the particular dot notation array path.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function get(string $path)
    {
        return arr_get($this->data, $path);
    }

    /**
     * Check if a particular dot notation array path exists or not in the given array.
     *
     * @param string $path
     *
     * @return bool
     */
    public function exists(string $path): bool
    {
        return arr_exists($this->data, $path);
    }

    /**
     * Check if the given input path is equal to NULL.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function isNull(string $path): bool
    {
        return arr_nulled($this->data, $path);
    }

    /**
     * Check if the given input path exists & is not equal to NULL.
     * 
     * @param string $path
     * 
     * @return bool
     */
    public function notNull(string $path): bool
    {
        return !arr_nulled($this->data, $path);
    }

    /**
     * Check if a particular dot notation array path exists & its value is not empty.
     *
     * @param string $path
     *
     * @return bool
     */
    public function filled(string $path): bool
    {
        return arr_filled($this->data, $path);
    }
}
