<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Presets;

use AdityaZanjad\Validator\Validator;

/**
 * Validate the given input data against the given validation rules.
 *
 * @param   array<int|string, mixed>        $data
 * @param   array<string, string|array>     $rules
 * @param   array<string, string>           $messages
 *
 * @return  \AdityaZanjad\Validator\Validator
 */
function validate(array $data, array $rules, array $messages = []): Validator
{
    return (new Validator($data, $rules, $messages))->validate();
}
