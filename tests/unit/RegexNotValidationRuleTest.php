<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\RegexNot;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RegexNot::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class RegexNotValidationRuleTest extends TestCase
{
    /**
     * Validate that the 'required_if' validation rule returns a validation error.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'number'    =>  'abcde',
            'email'     =>  'test.user@',
            'zip_code'  =>  '12345789',
            'phone'     =>  'Hello World! (123) 456-7890',
        ], [
            'number'    =>  'required|regex_not:/^[0-9]$/',
            'email'     =>  'required|regex_not:/^.+@.+$/i',
            'zip_code'  =>  'required|regex_not:/^\d{5}$/',
            'phone'     =>  'required|regex_not:/^\(\d{3}\)\s\d{3}-\d{4}$/',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->of('number'));
        $this->assertEmpty($validator->errors()->of('email'));
        $this->assertEmpty($validator->errors()->of('zip_code'));
        $this->assertEmpty($validator->errors()->of('phone'));
    }

    /**
     * Test that the 'required_if' validation succeeds.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'number'    =>  5,
            'email'     =>  'test.user@example.com',
            'zip_code'  =>  '12345',
            'phone'     =>  '(123) 456-7890',
        ], [
            'number'    =>  'required|regex_not:/^[0-9]$/',
            'email'     =>  'required|regex_not:/^.+@.+$/i',
            'zip_code'  =>  'required|regex_not:/^\d{5}$/',
            'phone'     =>  'required|regex_not:/^\(\d{3}\)\s\d{3}-\d{4}$/',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->of('number'));
        $this->assertNotEmpty($validator->errors()->of('email'));
        $this->assertNotEmpty($validator->errors()->of('zip_code'));
        $this->assertNotEmpty($validator->errors()->of('phone'));
    }
}
