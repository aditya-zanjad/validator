<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Same;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Same::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class SameValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'password'              =>  'mysecretpassword123',
            'password_confirmation' =>  'mysecretpassword123',
            'email'                 =>  'test@example.com',
            'email_confirmation'    =>  'test@example.com',
        ], [
            'password_confirmation' =>  'required|same:password',
            'email_confirmation'    =>  'required|same:email',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->of('password_confirmation'));
        $this->assertNull($validator->errors()->of('password_confirmation'));
        $this->assertEmpty($validator->errors()->of('email_confirmation'));
        $this->assertNull($validator->errors()->of('email_confirmation'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'password'              =>  'mysecretpassword123',
            'password_confirmation' =>  'mismatchedpassword',
            'email'                 =>  'test@example.com',
            'email_confirmation'    =>  'different@example.com',
        ], [
            'password_confirmation' =>  'required|same:password',
            'email_confirmation'    =>  'required|same:email',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->of('password_confirmation'));
        $this->assertIsArray($validator->errors()->of('password_confirmation'));
        $this->assertIsString($validator->errors()->firstOf('password_confirmation'));
        $this->assertNotEmpty($validator->errors()->of('email_confirmation'));
        $this->assertIsArray($validator->errors()->of('email_confirmation'));
        $this->assertIsString($validator->errors()->firstOf('email_confirmation'));
    }
}
