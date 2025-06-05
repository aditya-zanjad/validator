<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Fluents;

use function AdityaZanjad\Validator\Utils\arr_dot;
use function AdityaZanjad\Validator\Utils\arr_get;
use function AdityaZanjad\Validator\Utils\arr_exists;
use function AdityaZanjad\Validator\Utils\arr_filled;
use function AdityaZanjad\Validator\Utils\arr_not_null;

/**
 * @version 1.0
 */
class Input
{
    /**
     * The provided input data.
     *
     * @var array<int|string, mixed> $data
     */
    protected array $data;

    /**
     * The array dot notation paths to each input value.
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
        $this->data     =   $data;
        $this->paths    =   array_keys(arr_dot($this->data));
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
    public function keys(): array
    {
        return $this->paths;
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
        return !arr_not_null($this->data, $path);
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
        return arr_not_null($this->data, $path);
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
