<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;

use function AdityaZanjad\Validator\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredWithoutAll::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class RequiredWithoutAllValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testRequiredWithoutAllValidationRulePasses(): void
    {
        $validator = validate([
            'abc'   =>  '123',
            'def'   =>  null,
            'xyz'   =>  null
        ], [
            'abc'   =>  'required_without_all:def,ghi,jkl,xyz',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testRequiredWithoutAllValidationRuleFails(): void
    {
        $validator = validate([
            // 'abc'   => '123',
            // 'ghi'   => 'abc_def_ghi',
            // 'jkl'   =>  ['an indexed array'],
            // 'xyz'   =>  1234567890
        ], [
            'abc'   =>  'required_without_all:ghi,jkl,xyz|string|min:12',
            'ghi'   =>  'required_without_all:abc,jkl,xyz|string|min:10',
            'jkl'   =>  'required_without_all:abc,ghi,xyz|array|min:1',
            'xyz'   =>  'required_without_all:abc,ghi,jkl|numeric|integer|min:100000',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertIsArray($validator->errors()->all());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertIsString($validator->errors()->firstOf('ghi'));
        $this->assertNotNull($validator->errors()->firstOf('jkl'));
        $this->assertIsString($validator->errors()->firstOf('xyz'));
    }
}
