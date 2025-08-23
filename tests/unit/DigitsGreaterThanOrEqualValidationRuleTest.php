<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\DigitsGte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DigitsGte::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DigitsGreaterThanOrEqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits_gt:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'code'  =>  '123456', // Longer length
            'pin'   =>  12345678, // Much longer
            'abc'   =>  '12345.5789',
            'def'   =>  43581.234235235
        ], [
            'code'  =>  'digits_gt:5',
            'pin'   =>  'digits_gt:5',
            'abc'   =>  'digits_gt:4',
            'def'   =>  'digits_gt:3'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('code'));
        $this->assertNull($validator->errors()->firstOf('pin'));
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
    }

    /**
     * Assert that the validation rule 'digits_gt:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'code'  =>  '1234',   // Fails: too short
            'pin'   =>  '12345',  // Fails: length is equal
            'num'   =>  'abc',    // Fails: not all digits,
            'abc'   =>  '1234.4325'
        ], [
            'code'  =>  'digits_gt:5',
            'pin'   =>  'digits_gt:5',
            'num'   =>  'digits_gt:5',
            'abc'   =>  'digits_gt:5'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('code'));
        $this->assertNotNull($validator->errors()->firstOf('pin'));
        $this->assertNotNull($validator->errors()->firstOf('num'));
        $this->assertNotNull($validator->errors()->firstOf('abc'));
    }
}
