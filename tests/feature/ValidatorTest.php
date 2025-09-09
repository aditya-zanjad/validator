<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Error;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Validator::class)]
#[UsesClass(Input::class)]
#[UsesClass(Error::class)]
class ValidatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testEmptyRulesNotAllowed(): void
    {
        $input      =   new Input([]);
        $rules      =   [];
        $errors     =   new Error();
        $messages   =   [];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The parameter [rules] must not be empty. You must provide at least one validation rule.");
        new Validator($input, $rules, $errors, $messages);
    }

    /**
     * @return void
     */
    public function testInvalidRulesAreNotAccepted(): void
    {
        $input      =   new Input(['first' => 'abc']);
        $rules      =   [new stdClass()];
        $errors     =   new Error();
        $messages   =   [];
        $validator  =   new Validator($input, $rules, $errors, $messages);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The field [0] must have validation rules specified in [STRING] or [ARRAY] format.");
        $validator->validate();
    }
}