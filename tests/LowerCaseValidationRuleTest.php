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
final class LowerCaseValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testLowerCaseValidationRulePasses(): void
    {
        $validator = validate([
            'abc' => 'this is a small cased string!',
            'pqr' => 'anothersmallcasedstring. 1234! get on the dance floor!'
        ], [
            'abc' => 'required|string|lowercase',
            'pqr' => 'required|string|lowercase'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testLowerCaseValidationRuleFails(): void
    {
        $validator = validate([
            'abc'       =>  ['this is a string.'],
            'xyz'       =>  ['this is a string!' => 'this is a string !'],
            'array'     =>  'THIS IS AN UPPERCASED STRING!',
            'int'       =>  12345682385,
            'float'     =>  57832572.23478235,
            'object'    =>  (object) ['abc' => 1234]
        ], [
            'abc'       =>  'lowercase',
            'xyz'       =>  'lowercase',
            'array'     =>  'lowercase',
            'int'       =>  'lowercase',
            'float'     =>  'lowercase',
            'object'    =>  'lowercase'
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
}
