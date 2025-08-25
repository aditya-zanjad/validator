<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Traits;

use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Managers\Error;
use AdityaZanjad\Validator\Exceptions\ValidationFailed;

/**
 * @version 2.0
 */
trait ValidatorTrait
{
    /**
     * Validate the given input data against the given validation rules.
     *
     * @param   array<int|string, mixed>        $data
     * @param   array<string, string|array>     $rules
     * @param   array<string, string>           $messages
     * @param   bool                            $shouldThrowException
     *
     * @throws  \AdityaZanjad\Validator\Exceptions\ValidationFailed
     *
     * @return  \AdityaZanjad\Validator\Validator
     */
    final public function validate(array $data, array $rules, array $messages = [], bool $shouldThrowException = true): Validator
    {
        $validator = new Validator(new Input($data), $rules, new Error(), $messages);
        $validator->validate();

        if ($validator->failed() && $shouldThrowException) {
            throw new ValidationFailed($validator->errors()->first(), $validator->errors()->all());
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
    final public function validator(array $data, array $rules, array $messages = []): Validator
    {
        return new Validator(new Input($data), $rules, new Error(), $messages);
    }
}
