<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Traits;

use AdityaZanjad\Validator\Validator;
use function AdityaZanjad\Validator\Utils\validate;
use function AdityaZanjad\Validator\Utils\validator;

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
        return validate($data, $rules, $messages);
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
        return validator($data, $rules, $messages);
    }
}
