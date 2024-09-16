<?php

namespace AdityaZanjad\Validator\Interfaces;

interface ValidationRule
{
    /**
     * Perform the validation against the given attribute.
     *
     * @param   string  $attribute
     * @param   mixed   $value
     *
     * @return  bool
     */
    public function check(string $attribute, mixed $value): bool|string;
}
