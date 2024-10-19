<?php

namespace AdityaZanjad\Validator;

use function AdityaZanjad\Validator\Utils\array_path_value;
use function AdityaZanjad\Validator\Utils\array_path_exists;

/**
 * This class manages the input data that we want to validate.
 *
 * @author  Aditya Zanjad <adityazanjad474@gmail.com>
 * @version 1.0
 */
class Input
{
    /**
     * @param array<int|string, mixed> $data
     */
    public function __construct(protected array $data)
    {
        //
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
     * Get one/more of the provided input data based on the given parameter(s).
     *
     * @param string ...$fields
     *
     * @return mixed
     */
    public function get(string ...$fields): mixed
    {
        if (count($fields) === 1) {
            return array_path_value($this->data, $fields[0]);
        }

        $data = [];

        foreach ($fields as $field) {
            $data[$field] = array_path_value($this->data, $field);
        }

        return $data;
    }

    /**
     * Check whether or not the given array path(s) exist within the provided input data.
     *
     * @param string ...$fields
     *
     * @return bool|array<string, bool>
     */
    public function exists(string ...$fields): bool|array
    {
        if (count($fields) === 1) {
            return array_path_exists($this->data, $fields[0]);
        }

        $data = [];

        foreach ($fields as $field) {
            $data[$field] = array_path_exists($this->data, $field);
        }

        return $data;
    }
}
