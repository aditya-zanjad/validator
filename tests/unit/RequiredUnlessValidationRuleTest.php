<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\RequiredUnless;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredUnless::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class RequiredUnlessValidationRuleTest extends TestCase
{
    /**
     * Test that the 'required_if' validation succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  '123',
            'def'   =>  null,
            'xyz'   =>  null
        ], [
            'pqr'   =>  'required_unless:abc,123',
            'xyz'   =>  'required_unless:pqr,null',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->first());
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
    }

    /**
     * Validate that the 'required_if' validation rule returns a validation error.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   =>  'abc',
            'def'   =>  'Hello World!',
            'xyz'   =>  null
        ], [
            'pqr'   =>  'required_unless:abc,null|numeric|integer|min:1',
            'xyz'   =>  'required_unless:def,1234! Get on the dance floor!|numeric|integer|min:1',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->first());
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
    }
}
