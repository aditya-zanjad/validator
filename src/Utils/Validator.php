<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Utils;

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
    $validator = new Validator($data, $rules, $messages);
    return $validator->validate();
}

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
