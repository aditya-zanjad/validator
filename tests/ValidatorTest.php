<?php

namespace AdityaZanjad\Validator\Tests;

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Primitives\TypeStr;
use AdityaZanjad\Validator\Rules\Constraints\Required;
use AdityaZanjad\Validator\Rules\Constraints\RequiredIf;

#[CoversClass(TypeStr::class)]
#[UsesClass(Validator::class)]
#[CoversClass(Required::class)]
#[CoversClass(Validator::class)]
#[CoversClass(RequiredIf::class)]
final class ValidatorTest extends TestCase
{
    public function testRequiredRule()
    {
        $validator = new Validator([], [
            'abc' => 'required',
            'xyz' => 'string'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->allErrors());
    }

    public function testRequiredIfRule()
    {
        $validator = new Validator([
            'abc' => '123',
            'xyz' => '',
            'pqr' => null
        ], [
            'xyz' => 'required_if:abc,123',
            'pqr' => 'required_if:abc,468,479,1234'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->allErrors());
    }

    public function testStringRule()
    {
        $validator = new Validator([
            'abc'   =>  [],
        ], [
            'abc'   =>  'string',
            'xyz'   =>  'required|string',
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->allErrors());
        $this->assertEmpty($validator->firstErrorOf('pqr'));
    }

    public function testInValidationRule()
    {
        $validator = new Validator([
            'abc' => 123,
            'xyz' => 'abc'
        ], [
            'abc' => 'numeric|integer|in:123,456',
            'xyz' => 'numeric|integer|in:456'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->allErrors());
        $this->assertEmpty($validator->firstErrorOf('abc'));
        $this->assertNotEmpty($validator->firstErrorOf('xyz'));
    }
}
