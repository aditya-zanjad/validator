<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Digits;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Digits::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DigitsValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'pin' => 12345,
            'id'  => '98765',
            'abc' => '12345.5789',
            'def' => 43581.234235235
        ], [
            'pin' => 'digits:5',
            'id'  => 'digits:5',
            'abc' => 'digits:5',
            'def' => 'digits:5'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('pin'));
        $this->assertNull($validator->errors()->firstOf('id'));
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
    }

    /**
     * Assert that the validation rule 'digits:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'pin'       =>  1234,      // Fails: too short
            'id'        =>  '987654',  // Fails: too long
            'code'      =>  'abcde',  // Fails: not all digits
            'long_pin'  =>  '1234567891.23456789'
        ], [
            'pin'       =>  'digits:5',
            'id'        =>  'digits:5',
            'code'      =>  'digits:5',
            'long_pin'  =>  'digits:5',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('pin'));
        $this->assertNotNull($validator->errors()->firstOf('id'));
        $this->assertNotNull($validator->errors()->firstOf('code'));
        $this->assertNotNull($validator->errors()->firstOf('long_pin'));
    }
}
