<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\TypeArray;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeArray::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
final class ArrayValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testArrayValidationRulePasses(): void
    {
        $validator = validate([
            'abc'   =>  ['this is a string.'],
            'def'   =>  ['this is a string!' => 'this is a string !'],
            'ghi'   =>  [1, 2, 3, 4, 5, 6],
            'jkl'   =>  [12345682385],
            'mno'   =>  [[[[57832572.23478235]]]],
            'pqr'   =>  ['abc' => 1234]
        ], [
            'abc'   =>  'array',
            'def'   =>  'array',
            'ghi'   =>  'array',
            'jkl'   =>  'array',
            'mno'   =>  'array',
            'pqr'   =>  'array'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
        $this->assertEmpty($validator->errors()->firstOf('ghi'));
        $this->assertEmpty($validator->errors()->firstOf('jkl'));
        $this->assertEmpty($validator->errors()->firstOf('mno'));
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testArrayValidationRuleFails(): void
    {
        $validator = validate([
            'abc'   =>  'this is a string.',
            'def'   =>  -12311,
            'ghi'   =>  true,
            'jkl'   =>  new \stdClass(),
            'mno'   =>  57832572.23478235,
            'pqr'   =>  1234
        ], [
            'abc'   =>  'array',
            'def'   =>  'array',
            'ghi'   =>  'array',
            'jkl'   =>  'array',
            'mno'   =>  'array',
            'pqr'   =>  'array'
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
