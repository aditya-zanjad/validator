<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\TypeArray;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeArray::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class CustomErrorMessageValidationTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  ['this is a string.'],
            'def'   =>  ['this is a string!' => 'this is a string !'],
            'ghi'   =>  [1, 2, 3, 4, 5, 6],
            'jkl'   =>  [12345682385],
            'mno'   =>  [[[[57832572.23478235]]]],
            'pqr'   =>  ['abc' => 1234],
            'xyz'   =>  new \ArrayObject()
        ], [
            'abc'   =>  'array',
            'def'   =>  'array',
            'ghi'   =>  'array',
            'jkl'   =>  'array',
            'mno'   =>  'array',
            'pqr'   =>  'array',
            'xyz'   =>  'array'
        ], [
            'abc.array' => 'The field :{field} should be an array',
            'def.array' => 'The field :{field} should be an array',
            'ghi.array' => 'The field :{field} should be an array',
            'jkl.array' => 'The field :{field} should be an array',
            'mno.array' => 'The field :{field} should be an array',
            'pqr.array' => 'The field :{field} should be an array',
            'xyz.array' => 'The field :{field} should be an array',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertEmpty($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertEmpty($validator->errors()->firstOf('jkl'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
        $this->assertEmpty($validator->errors()->firstOf('mno'));
        $this->assertNull($validator->errors()->firstOf('mno'));
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'           =>  'this is a string.',
            'def'           =>  -12311,
            'ghi'           =>  true,
            'jkl'           =>  new \stdClass(),
            'mno'           =>  57832572.23478235,
            'pqr'           =>  1234,
            'xyz'           =>  ['123' => ['Hello World!'], '456' => null]
        ], [
            'abc'           =>  'array',
            'def'           =>  'array',
            'ghi'           =>  'array',
            'jkl'           =>  'array',
            'mno'           =>  'array',
            'pqr'           =>  'array',
            'xyz'           =>  'required|array',
            'xyz.*'         =>  'required_with:xyz|string'
        ], [
            'abc.array'     =>  'The field :{field} should be an array.',
            'def.array'     =>  'The field :{field} should be an array.',
            'ghi.array'     =>  'The field :{field} should be an array.',
            'jkl.array'     =>  'The field :{field} should be an array.',
            'mno.array'     =>  'The field :{field} should be an array.',
            'pqr.array'     =>  'The field :{field} should be an array.',
            'xyz.array'     =>  'The field :{field} should be an array.',
            'xyz.*.string'  =>  'The field :{field} should be a string.'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertIsString($validator->errors()->firstOf('abc'));
        $this->assertEquals($validator->errors()->firstOf('abc'), 'The field abc should be an array.');
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertIsString($validator->errors()->firstOf('def'));
        $this->assertEquals($validator->errors()->firstOf('def'), 'The field def should be an array.');
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
        $this->assertIsString($validator->errors()->firstOf('ghi'));
        $this->assertEquals($validator->errors()->firstOf('ghi'), 'The field ghi should be an array.');
        $this->assertNotEmpty($validator->errors()->firstOf('jkl'));
        $this->assertIsString($validator->errors()->firstOf('jkl'));
        $this->assertEquals($validator->errors()->firstOf('jkl'), 'The field jkl should be an array.');
        $this->assertNotEmpty($validator->errors()->firstOf('mno'));
        $this->assertIsString($validator->errors()->firstOf('mno'));
        $this->assertEquals($validator->errors()->firstOf('mno'), 'The field mno should be an array.');
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertIsString($validator->errors()->firstOf('pqr'));
        $this->assertEquals($validator->errors()->firstOf('pqr'), 'The field pqr should be an array.');

        // // Custom error messages for the input paths containing wildcard parameters.
        // $this->assertNotEmpty($validator->errors()->firstOf('xyz.123'));
        // $this->assertIsString($validator->errors()->firstOf('xyz.123'));
        // $this->assertEquals($validator->errors()->firstOf('xyz.123'), 'The field xyz.123 should be a string.');
        // $this->assertNotEmpty($validator->errors()->firstOf('xyz.456'));
        // $this->assertIsString($validator->errors()->firstOf('xyz.456'));
        // $this->assertEquals($validator->errors()->firstOf('xyz.456'), 'The field xyz.456 should be a string.');
    }
}
