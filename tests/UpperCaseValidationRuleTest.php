<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\TypeString as TypeStr;

use function AdityaZanjad\Validator\Utils\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeStr::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
final class UpperCaseValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testStringValidationRulePasses(): void
    {
        $validator = validate([
            'text' => 'HELLO WORLD HOW ARE YOU'
        ], [
            'english'   =>  'string',
            'hindi'     =>  'string',
            'japanese'  =>  'string'
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('english'));
        $this->assertNull($validator->errors()->firstOf('hindi'));
        $this->assertNull($validator->errors()->firstOf('japanese'));
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testStringValidationRuleFails(): void
    {
        $validator = validate([
            'abc'       =>  ['this is a string.'],
            'xyz'       =>  ['this is a string!' => 'this is a string !'],
            'array'     =>  [1, 2, 3, 4, 5, 6],
            'int'       =>  12345682385,
            'float'     =>  57832572.23478235,
            'object'    =>  (object) ['abc' => 1234]
        ], [
            'abc'       =>  'string',
            'xyz'       =>  'string',
            'array'     =>  'string',
            'int'       =>  'string',
            'float'     =>  'string',
            'object'    =>  'string'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
        $this->assertNotEmpty($validator->errors()->firstOf('array'));
        $this->assertNotEmpty($validator->errors()->firstOf('int'));
        $this->assertNotEmpty($validator->errors()->firstOf('float'));
        $this->assertNotEmpty($validator->errors()->firstOf('object'));

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
