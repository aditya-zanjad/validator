<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Validator;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Rules\RequiredIf;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredIf::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
class RequiredIfRuleTest extends TestCase
{
    /**
     * Validate that the 'required_if' validation rule returns a validation error.
     *
     * @return void
     */
    public function testRequiredIfValidationFails(): void
    {
        $validator = validate([
            'abc' => 123,
            'xyz' => null
        ], [
            'abc'   =>  'required',
            'xyz'   =>  'required_if:abc,123'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->first());
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
    }

    /**
     * Test that the 'required_if' validation succeeds.
     *
     * @return void
     */
    public function testRequiredIfValidationSucceeds(): void
    {
        $validator = validate([
            'abc'   =>  123,
            'def'   =>  null,
            'pqr'   =>  123,
            'xyz'   =>  456
        ], [
            'abc'   =>  'required',
            'def'   =>  'string',
            'pqr'   =>  'required_if:def,null',
            'xyz'   =>  'required_if:abc,456,789,12345,abc,xyz,908234,true,false,0,1'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->first());
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
    }
}
