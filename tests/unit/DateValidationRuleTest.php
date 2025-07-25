<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Date;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Date::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class DateValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  '1994-05-11',
            'def'   =>  '11-05-1994',
            'ghi'   =>  '05-11-1994',
            'jkl'   =>  '11 May, 1994',
            'mno'   =>  '11/05/1994',
            'pqr'   =>  '05/11/1994',
            'uvw'   =>  'May 11, 1994',
            'xyz'   =>  '1994/05/11',
            'zyx'   =>  '1750687200'
        ], [
            'abc'   =>  'date',
            'def'   =>  'date:d-m-Y',
            'ghi'   =>  'date:m-d-Y',
            'jkl'   =>  'date: d M, Y',
            'mno'   =>  'date: d/m/Y',
            'pqr'   =>  'date:m/d/Y',
            'uvw'   =>  'date: F d, Y',
            'xyz'   =>  'date:Y/m/d',
            'zyx'   =>  'date'
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
        $this->assertEmpty($validator->errors()->firstOf('zyx'));
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
            'pqr'   =>  1234,
            'xyz'   =>  '2025-06-25 10:20:20 1234!fda'
        ], [
            'abc'   =>  'date',
            'def'   =>  'date:d-m-Y',
            'ghi'   =>  'date',
            'jkl'   =>  'date:d m, Y',
            'mno'   =>  'date',
            'pqr'   =>  'date:m Y, d',
            'xyz'   =>  'date:Y-m-d'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
        $this->assertNotEmpty($validator->errors()->firstOf('jkl'));
        $this->assertNotEmpty($validator->errors()->firstOf('mno'));
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
    }
}
