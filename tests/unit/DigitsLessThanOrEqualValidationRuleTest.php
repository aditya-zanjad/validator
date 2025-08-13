<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\DigitsLte;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(DigitsLte::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class DigitsLessThanOrEqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits_lte:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'code'  =>  '12345',  // Exact length
            'pin'   =>  1234,     // Shorter length,
            'abc'   =>  1236.1235,
        ], [
            'code'  =>  'digits_lte:5',
            'pin'   =>  'digits_lte:5',
            'abc'   =>  'digits_lte:5'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('code'));
        $this->assertNull($validator->errors()->firstOf('pin'));
        $this->assertNull($validator->errors()->firstOf('abc'));
    }

    /**
     * Assert that the validation rule 'digits_lte:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'code'  =>  '123456', // Fails: too long
            'pin'   =>  'abc',    // Fails: not all digits
            'num'   =>  12345678910,
            'abc'   =>  '1234135.3141341341343'
        ], [
            'code'  =>  'digits_lte:5',
            'pin'   =>  'digits_lte:5',
            'num'   =>  'digits_lte:5',
            'abc'   =>  'digits_lte:5'
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
