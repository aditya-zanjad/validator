<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\DigitsLt;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DigitsLt::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DigitsLessThanValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits_lt:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'code'  =>  '1234',   // Shorter length
            'pin'   =>  123,      // Shorter length
        ], [
            'code'  =>   'digits_lt:5',
            'pin'   =>   'digits_lt:5'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('code'));
        $this->assertNull($validator->errors()->firstOf('pin'));
    }

    /**
     * Assert that the validation rule 'digits_lt:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'code'  =>  '12345',  // Fails: length is equal
            'pin'   =>  '123456', // Fails: too long
            'num'   =>  12345678910
        ], [
            'code'  =>  'digits_lt:5',
            'pin'   =>  'digits_lt:5',
            'num'   =>  'digits_lt:5'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('code'));
        $this->assertNotNull($validator->errors()->firstOf('pin'));
        $this->assertNotNull($validator->errors()->firstOf('num'));
    }
}
