<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\TypeString as TypeStr;

use function AdityaZanjad\Validator\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeStr::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class UpperCaseValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testUpperCaseValidationRulePasses(): void
    {
        $validator = validate([
            'text' => 'HELLO WORLD HOW ARE YOU? 1234@/0--=+()! GET ON THE DANCE FLOOR!'
        ], [
            'text' => 'required|string|uppercase'
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('text'));
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testUpperCaseValidationRuleFails(): void
    {
        $validator = validate([
            'abc'       =>  ['this is a string.'],
            'xyz'       =>  ['this is a string!' => 'this is a string !'],
            'array'     =>  [1, 2, 3, 4, 5, 6],
            'int'       =>  12345682385,
            'float'     =>  57832572.23478235,
            'object'    =>  (object) ['abc' => 1234],
            'string'    =>  'this is a lowercase string!'
        ], [
            'abc'       =>  'required|uppercase',
            'xyz'       =>  'required|uppercase',
            'array'     =>  'required|uppercase',
            'int'       =>  'required|uppercase',
            'float'     =>  'required|uppercase',
            'object'    =>  'required|uppercase',
            'string'    =>  'required|string|uppercase'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
        $this->assertNotEmpty($validator->errors()->firstOf('array'));
        $this->assertNotEmpty($validator->errors()->firstOf('int'));
        $this->assertNotEmpty($validator->errors()->firstOf('float'));
        $this->assertNotEmpty($validator->errors()->firstOf('object'));
        $this->assertNotEmpty($validator->errors()->firstOf('string'));
    }

    /**
     * Assert that the validation rule is skipped when given field is missing or is set to null.
     *
     * @return void
     */
    public function testStringValidationRuleIsSkipped()
    {
        $validator = validate([
            'xyz' => null
        ], [
            'abc'   =>  'string',
            'xyz'   =>  'string'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
    }

    /**
     * Assert that the validation rule is skipped when given field is missing or is set to null.
     *
     * @return void
     */
    public function testStringValidationRuleIsAppliedToRequiredFields()
    {
        $validator = validate([
            'xyz' => null
        ], [
            'abc' => 'string|required',
            'xyz' => 'string|required'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertNotNull($validator->errors()->firstOf('xyz'));
    }
}
