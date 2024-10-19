<?php

namespace AdityaZanjad\Validator;

use function AdityaZanjad\Validator\Utils\array_path_exists;
use function AdityaZanjad\Validator\Utils\array_path_value;

class Input
{
    public function __construct(protected array $data)
    {
        //
    }

    public function all()
    {
        //
    }

    public function get(string $field)
    {
        return array_path_value($this->data, $field);
    }

    public function exists(string $field)
    {
        return array_path_exists($this->data, $field);
    }
}