<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Presets;

use AdityaZanjad\Validator\Validator;

/**
 * Obtain the instance of the validator to customize and perform the validation.
 *
 * @param   array<int|string, mixed>      $data
 * @param   array<string, string|array>   $rules
 * @param   array<string, string>         $messages
 *
 * @return  \AdityaZanjad\Validator\Validator
 */
function validator(array $data, array $rules, array $messages = []): Validator
{
    return new Validator($data, $rules, $rules, $messages);
}
