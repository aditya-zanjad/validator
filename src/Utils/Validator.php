<?php

namespace AdityaZanjad\Validator\Utils;

use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\ErrorsManager;
use AdityaZanjad\Validator\Managers\InputsManager;
use AdityaZanjad\Validator\Exceptions\ValidationFailed;

/**
 * Validate the given input data against the given validation rules.
 *
 * @param   array<int|string, mixed>        $data
 * @param   array<string, string|array>     $rules
 * @param   array<string, string>           $messages
 *
 * @throws  \AdityaZanjad\Validator\Exceptions\ValidationFailed
 *
 * @return  \AdityaZanjad\Validator\Validator
 */
function validate(array $data, array $rules, array $messages = []): Validator
{
    // Perform the validation with pre-defined settings.
    $validator = validator($data, $rules, $messages)
        ->stopOnFirstFailure()
        ->validate();

    if ($validator->failed()) {
        throw new ValidationFailed($validator->errors()->first());
    }

    return $validator;
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
    $input      =   new InputsManager($data);
    $error      =   new ErrorsManager();

    return new Validator($input, $error, $rules, $messages);
}
