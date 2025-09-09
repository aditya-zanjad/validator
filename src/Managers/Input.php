<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Managers;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;
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
        $arr        =   new RecursiveArrayIterator($this->data);
        $arr        =   new RecursiveIteratorIterator($arr, RecursiveIteratorIterator::SELF_FIRST);
        $result     =   [];
        $nestedKeys =   [];

        foreach ($arr as $key => $value) {
            $nestedKeys[$arr->getDepth()] = $key;

            if (\is_array($value) && !empty($value)) {
                continue;
            }

            $nestedKeys =   \array_slice($nestedKeys, 0, $arr->getDepth() + 1);
            $result[]   =   \implode('.', $nestedKeys);
        }

        return $result;
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
    public function get(string $path): mixed
    {
        $ref    =   &$this->data;
        $keys   =   \explode('.', $path);

        foreach ($keys as $key) {
            if (!isset($ref[$key])) {
                return null;
            }

            $ref = &$ref[$key];
        }

        return $ref;
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
        $ref    =   &$this->data;
        $keys   =   \explode('.', $path);

        foreach ($keys as $key) {
            if (!\array_key_exists($key, $ref) || !\is_array($ref[$key])) {
                return false;
            }

            $ref = &$ref[$key];
        }

        return true;
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
        $ref    =   &$this->data;
        $keys   =   \explode('.', $path);

        foreach ($keys as $key) {
            if (!isset($ref[$key])) {
                return true;
            }

            $ref = &$ref[$key];
        }

        return false;
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
        $ref    =   &$this->data;
        $keys   =   \explode('.', $path);

        foreach ($keys as $key) {
            // The field must be set & not empty.
            if (!isset($ref[$key]) || empty($ref[$key])) {
                return false;
            }

            $ref = &$ref[$key];
        }

        return true;
    }
}
