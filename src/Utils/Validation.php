<?php

namespace AdityaZanjad\Validator\Utils;

use AdityaZanjad\Validator\Error;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Validator;

/**
 * Instantiate the validator & validate the given input data.
 *
 * @param   array<string, string|array>   $rules
 * @param   array<int|string, mixed>      $data
 * @param   array<string, string>         $messages
 *
 * @return  \AdityaZanjad\Validator\Validator
 */
function validator(array $data, array $rules, array $messages = [])
{
    return new Validator(new Input($data), $rules, $messages, new Error());
}

/**
 * Instantiate the validator & validate the given input data.
 *
 * @param   array<string, string|array>   $rules
 * @param   array<int|string, mixed>      $data
 * @param   array<string, string>         $messages
 *
 * @return  \AdityaZanjad\Validator\Validator
 */
function validate(array $data, array $rules, array $messages = [])
{
    return validator($data, $rules, $messages)->validate();
}
