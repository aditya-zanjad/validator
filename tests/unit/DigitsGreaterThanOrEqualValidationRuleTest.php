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
     * Assert that the validation rule 'digits_gte:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'code' => '12345',  // Exact length
            'pin'  => 123456,   // Longer length
        ], [
            'code' => 'digits_gte:5',
            'pin'  => 'digits_gte:5'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('code'));
        $this->assertNull($validator->errors()->firstOf('pin'));
    }

    /**
     * Assert that the validation rule 'digits_gte:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'code' => '1234',   // Fails: too short
            'pin'  => 'abc',    // Fails: not all digits
        ], [
            'code' => 'digits_gte:5',
            'pin'  => 'digits_gte:5'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('code'));
        $this->assertNotNull($validator->errors()->firstOf('pin'));
    }
}
