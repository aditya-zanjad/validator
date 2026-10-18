<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Error;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\Req;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Same;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\StrMin;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Validator::class)]
#[CoversClass(StrMin::class)]
#[CoversClass(Req::class)]
#[CoversClass(Same::class)]
#[CoversClass(Email::class)]
final class ValidatorFeatureTest extends TestCase
{
    public function testValidationPasses(): void
    {
        $input = new Input([
            'first_name'        =>  null,
            'last_name'         =>  'Zanjad',
            'email'             =>  'aditya@zanjad.family',
            'password'          =>  '12345678',
            'confirm_password'  =>  '12345678'
        ]);

        $validator = new Validator($input, [
            'first_name'        =>  'str_min:10',
            'last_name'         =>  'req|str_min:3',
            'email'             =>  'req|email',
            'password'          =>  'req|str_min:6',
            'confirm_password'  =>  'req|str|same:password'
        ]);

        $validator->validate();
        $this->assertTrue($validator->passed());
        $this->assertEmpty($validator->errors()->all());
    }
}
