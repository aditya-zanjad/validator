<?php

namespace AdityaZanjad\Validator\Utils;

use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Error;
use AdityaZanjad\Validator\Fluents\Input;

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
    // Perform the validation with pre-defined settings.
    return validator($data, $rules, $messages)->validate();
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
    $input      =   new Input($data);
    $error      =   new Error();

    return new Validator($input, $error, $rules, $messages);
}
