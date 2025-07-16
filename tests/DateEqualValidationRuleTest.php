<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\DateEqual;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DateEqual::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class DateEqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testDateEqualValidationRulePasses(): void
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
            'zyx'   =>  '768614400',
            'wvu'   =>  '768614400',
        ], [
            'abc'   =>  'date|date_eq:1994-05-11',
            'def'   =>  'date:d-m-Y|date_eq:11-05-1994',
            'ghi'   =>  'date:m-d-Y|date_eq:05-11-1994',
            'jkl'   =>  'date: d M, Y|date_eq:11 May\, 1994',
            'mno'   =>  'date: d/m/Y|date_eq: 11/05/1994    ',
            'pqr'   =>  'date:m/d/Y|date_eq:05/11/1994',
            'uvw'   =>  'date: F d, Y|date_eq:May 11\, 1994',
            'xyz'   =>  'date:Y/m/d|date_eq:768614400',
            'zyx'   =>  'date|date_eq:1994-05-11',
            'wvu'   =>  'date|date_eq:768614400'
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
        $this->assertEmpty($validator->errors()->firstOf('wvu'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testDateEqualValidationRuleFails(): void
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
            'zyx'   =>  '783993600'
        ], [
            'abc'   =>  'date|date_eq:1994-05-12',
            'def'   =>  'date:d-m-Y|date_eq:10-05-1994',
            'ghi'   =>  'date:m-d-Y|date_eq:09-11-1994',
            'jkl'   =>  'date: d M, Y|date_eq:11 Aug\, 1994',
            'mno'   =>  'date: d/m/Y|date_eq: 11/12/1994    ',
            'pqr'   =>  'date:m/d/Y|date_eq:05/11/1995',
            'uvw'   =>  'date: F d, Y|date_eq:Sep 12\, 2015',
            'xyz'   =>  'date:Y/m/d|date_eq:1994-06-12',
            'zyx'   =>  'date|date_eq:1752664339'
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
        $this->assertNotEmpty($validator->errors()->firstOf('zyx'));
    }
}
