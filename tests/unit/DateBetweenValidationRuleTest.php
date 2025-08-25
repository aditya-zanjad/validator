<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateBetween;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DateBetween::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DateBetweenValidationRuleTest extends TestCase
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
            // 'zyx'   =>  '783993600'
        ], [
            'abc'   =>  'date_between:1994-05-10,1994-05-12',
            'def'   =>  'date_between:10-05-1994, 12-05-1994',
            'ghi'   =>  'date_between:05-10-1994, 05-12-1994',
            'jkl'   =>  'date_between:10 May\, 1994, May 12\, 1994',
            'mno'   =>  'date_between: 10/05/1994, 12/05/1994',
            'pqr'   =>  'date_between:05/10/1994,05/12/1994',
            'uvw'   =>  'date_between:May 10\, 1994, 12 May\, 1994',
            'xyz'   =>  'date_between:1994-05-10,1994-05-12',
            // 'zyx'   =>  'date_between:781315200,786585600'
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
            'abc'   =>  '1994-05-13',
            'def'   =>  '15-05-1994',
            'ghi'   =>  '05-25-1994',
            'jkl'   =>  '01 May, 1994',
            'mno'   =>  '09/05/1994',
            'pqr'   =>  '05/08/1994',
            'uvw'   =>  'May 14, 1994',
            'xyz'   =>  '1994/05/16',
            // 'zyx'   =>  '768614400'
        ], [
            'abc'   =>  'date_between:1994-05-10,1994-05-12',
            'def'   =>  'date_between:10-05-1994, 12-05-1994',
            'ghi'   =>  'date_between:05-10-1994, 05-12-1994',
            'jkl'   =>  'date_between:10 May\, 1994, May 12\, 1994',
            'mno'   =>  'date_between: 10/05/1994, 12/05/1994',
            'pqr'   =>  'date_between:05/10/1994,05/12/1994',
            'uvw'   =>  'date_between:May 10\, 1994, 12 May\, 1994',
            'xyz'   =>  'date_between:1994-05-10,1994-05-12',
            // 'zyx'   =>  'date_between:781315200,786585600'
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
