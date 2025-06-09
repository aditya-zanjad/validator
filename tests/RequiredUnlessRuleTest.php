<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\RequiredUnless;

use function AdityaZanjad\Validator\Utils\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredUnless::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
class RequiredUnlessRuleTest extends TestCase
{
    /**
     * Test that the 'required_if' validation succeeds.
     *
     * @return void
     */
    public function testRequiredIfValidationPasses(): void
    {
        $validator = validate([
            'abc'   =>  'abc',
            'def'   =>  null,
            'pqr'   =>  123,
            'xyz'   =>  123
        ], [
            'abc'   =>  'required_unless:xyz,456|string|min:3',
            'def'   =>  'required_unless:abc,abc',
            'pqr'   =>  'required_unless:def,123|numeric|integer|min:1',
            'xyz'   =>  'required_unless:abc,null|numeric|integer|min:1',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->first());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
    }

    /**
     * Validate that the 'required_if' validation rule returns a validation error.
     *
     * @return void
     */
    public function testRequiredUnlessValidationFails(): void
    {
        $validator = validate([
            'abc'   =>  '123',
            'def'   =>  'Hello World!',
            'zyx'   =>  null,
            'pqr'   =>  123,
            'xyz'   =>  456
        ], [
            'abc'   =>  'required_unless:xyz,456|string|min:3',
            'def'   =>  'required_unless:abc,123',
            'pqr'   =>  'required_unless:zyx,null|numeric|integer|min:1',
            'xyz'   =>  'required_unless:abc,123|numeric|integer|min:1',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->first());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
    }
}
