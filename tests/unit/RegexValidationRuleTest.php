<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Regex;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Regex::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class RegexValidationRuleTest extends TestCase
{
    /**
     * Validate that the 'required_if' validation rule returns a validation error.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'number'    =>  5,
            'email'     =>  'test.user@example.com',
            'zip_code'  =>  '12345',
            'phone'     =>  '(123) 456-7890',
        ], [
            'number'    =>  'required|regex:/^[0-9]$/',
            'email'     =>  'required|regex:/^.+@.+$/i',
            'zip_code'  =>  'required|regex:/^\d{5}$/',
            'phone'     =>  'required|regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
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
            'number'    =>  'abc', // Invalid character
            'zip_code'  =>  '123456', // Invalid length
            'phone'     =>  '123-456-7890', // Invalid structure
        ], [
            'number'    =>  'required|regex:/^[0-9]$/',
            'zip_code'  =>  'required|regex:/^\d{5}$/',
            'phone'     =>  'required|regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
    }
}
