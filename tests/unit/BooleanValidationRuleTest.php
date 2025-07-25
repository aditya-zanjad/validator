<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeBoolean::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class BooleanValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  false,
            'def'   =>  true,
            'ghi'   =>  1,
            'jkl'   =>  0,
            'mno'   =>  'false',
            'pqr'   =>  'true',
            'uvw'   =>  '1',
            'xyz'   =>  '0'
        ], [
            'abc'   =>  'boolean',
            'def'   =>  'boolean',
            'ghi'   =>  'boolean',
            'jkl'   =>  'boolean',
            'mno'   =>  'boolean',
            'pqr'   =>  'boolean',
            'uvw'   =>  'boolean',
            'xyz'   =>  'boolean',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
        $this->assertEmpty($validator->errors()->firstOf('ghi'));
        $this->assertEmpty($validator->errors()->firstOf('jkl'));
        $this->assertEmpty($validator->errors()->firstOf('mno'));
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
        $this->assertEmpty($validator->errors()->firstOf('uvw'));
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   =>  'this is a string.',
            'def'   =>  -12311,
            'ghi'   =>  'truth',
            'jkl'   =>  new \stdClass(),
            'mno'   =>  57832572.23478235,
            'pqr'   =>  1234
        ], [
            'abc'   =>  'boolean',
            'def'   =>  'boolean',
            'ghi'   =>  'boolean',
            'jkl'   =>  'boolean',
            'mno'   =>  'boolean',
            'pqr'   =>  'boolean'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
        $this->assertNotEmpty($validator->errors()->firstOf('jkl'));
        $this->assertNotEmpty($validator->errors()->firstOf('mno'));
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
    }
}
