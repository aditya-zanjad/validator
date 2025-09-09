<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\DigitsGt;
use AdityaZanjad\Validator\Rules\DigitsGte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DigitsGt::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DigitsGreaterThanValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits_gt:5' succeeds.
     *
     * @return void
     */
    public function testDigitsGtAssertionsPass(): void
    {
        $validator = validate([
            'code'  =>  '123456', // Longer length
            'pin'   =>  12345678, // Much longer,
            'abc'   =>  '1234135.134134134'
        ], [
            'code'  =>  'digits_gt:5',
            'pin'   =>  'digits_gt:5',
            'abc'   =>  'digits_gt:5'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('code'));
        $this->assertNull($validator->errors()->firstOf('pin'));
        $this->assertNull($validator->errors()->firstOf('abc'));
    }
    
    /**
     * Assert that the validation rule 'digits_gt:5' fails.
     *
     * @return void
     */
    public function testDigitsGtAssertionsFail(): void
    {
        $validator = validate([
            'code'  =>  '1234',   // Fails: too short
            'pin'   =>  '12345',  // Fails: length is equal
            'num'   =>  'abc',    // Fails: not all digits
            'abc'   =>  12.134134134
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
