<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\DateLte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DateLte::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DateLessThanOrEqualValidationRuleTest extends TestCase
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
            'mno'   =>  '10/05/1994',
            'pqr'   =>  '05/11/1994',
            'uvw'   =>  'May 11, 1994',
            // 'xyz'   =>  '1994/05/11',
            // 'zyx'   =>  '768614400',
            // 'wvu'   =>  '768614400',
        ], [
            'abc'   =>  'date_lte:1994-05-11',
            'def'   =>  'date_lte:11-05-2025',
            'ghi'   =>  'date_lte:05-11-1994',
            'jkl'   =>  'date_lte:12 September\, 1995',
            'mno'   =>  'date_lte: 11/05/1994    ',
            'pqr'   =>  'date_lte:10/11/1994',
            'uvw'   =>  'date_lte:June 01\, 2000',
            // 'xyz'   =>  'date_lte:768700800',
            // 'zyx'   =>  'date_lte:2025-05-11',
            // 'wvu'   =>  'date_lte:768614400'
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
        // $this->assertEmpty($validator->errors()->firstOf('xyz'));
        // $this->assertEmpty($validator->errors()->firstOf('zyx'));
        // $this->assertEmpty($validator->errors()->firstOf('wvu'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
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
            // 'zyx'   =>  '768700800'
        ], [
            'abc'   =>  'date_lte:1994-05-03',
            'def'   =>  'date_lte:10-05-1994',
            'ghi'   =>  'date_lte:05-10-1994',
            'jkl'   =>  'date_lte:11 Jan\, 1994',
            'mno'   =>  'date_lte: 01/02/1994    ',
            'pqr'   =>  'date_lte:05/11/1993',
            'uvw'   =>  'date_lte:March 11\, 1994',
            'xyz'   =>  'date_lte:1993-12-11',
            // 'zyx'   =>  'date_lte:768614400'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
        $this->assertNotEmpty($validator->errors()->firstOf('jkl'));
        $this->assertNotEmpty($validator->errors()->firstOf('mno'));
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNotEmpty($validator->errors()->firstOf('uvw'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
        // $this->assertNotEmpty($validator->errors()->firstOf('zyx'));
    }
}
