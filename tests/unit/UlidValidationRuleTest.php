<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\ULID;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(ULID::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class UlidValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate(
            [
                'abc'   =>  '01AN6QW4K6J4EAXF5TV73Y4G0W',
                'def'   =>  '01AN6QW4K6J4EAXF5TV73Y4G0W',
            ],
            [
                'abc'   =>  'ulid',
                'def'   =>  'ulid',
            ]
        );

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   =>  '01AN6QW4K6J4EAXF5TV73Y4G0',
            'def'   =>  '01AN6QW4K6J4EAXF5TV73Y4G0WA',
            'ghi'   =>  '01AN6QW4K6J4EAXF5TV73Y4G0I',
        ], [
            'abc'   =>  'ulid',
            'def'   =>  'ulid',
            'ghi'   =>  'ulid',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
    }
}
