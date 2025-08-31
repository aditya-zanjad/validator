<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\UUID;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(UUID::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class UuidValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'v1'    =>  '123e4567-e89b-1d3c-a456-426655440000',
            'v2'    =>  '123e4567-e89b-2d3c-a456-426655440000',
            'v3'    =>  '123e4567-e89b-3d3c-a456-426655440000',
            'v4'    =>  'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'v5'    =>  'f47ac10b-58cc-5372-a567-0e02b2c3d479',
            'v6'    =>  '123e4567-e89b-6d3c-a456-426655440000',
            'v7'    =>  '123e4567-e89b-7d3c-a456-426655440000',
            'v8'    =>  '123e4567-e89b-8d3c-a456-426655440000',
        ], [
            'v1'    =>  'uuid: v1',
            'v2'    =>  'uuid: v2',
            'v3'    =>  'uuid: v3',
            'v4'    =>  'uuid: v4',
            'v5'    =>  'uuid: v5',
            'v6'    =>  'uuid: v6',
            'v7'    =>  'uuid: v7',
            'v8'    =>  'uuid: v8',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('v1'));
        $this->assertEmpty($validator->errors()->firstOf('v2'));
        $this->assertEmpty($validator->errors()->firstOf('v3'));
        $this->assertEmpty($validator->errors()->firstOf('v4'));
        $this->assertEmpty($validator->errors()->firstOf('v5'));
        $this->assertEmpty($validator->errors()->firstOf('v6'));
        $this->assertEmpty($validator->errors()->firstOf('v7'));
        $this->assertEmpty($validator->errors()->firstOf('v8'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'v1'    =>  '123e4567-e89b-4d3c-a456-426655440000',
            'v2'    =>  '123e4567-e89b-5d3c-a456-426655440000',
            'v3'    =>  '123e4567-e89b-4d3c-a456-426655440000',
            'v4'    =>  'f47ac10b-58cc-4372-c567-0e02b2c3d479',
            'v5'    =>  'f47ac10b-58cc-5372-a567-0e02b2c3d47g',
            'v6'    =>  '123e4567-e89b-6d3c-a456-4266554400',
            'v7'    =>  'f47ac10b-58cc-7372-c567-0e02b2c3d479',
            'v8'    =>  'This is not a UUID at all',
        ], [
            'v1'    =>  'uuid:v1',
            'v2'    =>  'uuid:v2',
            'v3'    =>  'uuid:v3',
            'v4'    =>  'uuid:v4',
            'v5'    =>  'uuid:v5',
            'v6'    =>  'uuid:v6',
            'v7'    =>  'uuid:v7',
            'v8'    =>  'uuid:v8',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('v1'));
        $this->assertNotEmpty($validator->errors()->firstOf('v2'));
        $this->assertNotEmpty($validator->errors()->firstOf('v3'));
        $this->assertNotEmpty($validator->errors()->firstOf('v4'));
        $this->assertNotEmpty($validator->errors()->firstOf('v5'));
        $this->assertNotEmpty($validator->errors()->firstOf('v6'));
        $this->assertNotEmpty($validator->errors()->firstOf('v7'));
        $this->assertNotEmpty($validator->errors()->firstOf('v8'));
    }
}
