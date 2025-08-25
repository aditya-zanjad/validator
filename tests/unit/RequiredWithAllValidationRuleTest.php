<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\RequiredWithAll;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredWithAll::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class RequiredWithAllValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   => '123',
            'def'   => null,
            'ghi'   => 'abc_def_ghi',
            'jkl'   =>  ['an indexed array'],
            'xyz'   =>  '1234! Get on the dance floor!'
        ], [
            'xyz'   =>  'required_with_all:abc,ghi,jkl,xyz|string|min:12',
            'zyx'   =>  'required_with_all:abc,def,ghi,jkl,pqr|string|min:12',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   => '123',
            'ghi'   => 'abc_def_ghi',
            'jkl'   =>  ['an indexed array'],
            'xyz'   =>  null
        ], [
            'xyz'   =>  'required_with_all:abc,ghi,jkl|string|min:12',
            'zyx'   =>  'required_with_all:abc,ghi,jkl|string|min:12',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertIsArray($validator->errors()->all());
        $this->assertNotNull($validator->errors()->firstOf('xyz'));
        $this->assertIsString($validator->errors()->firstOf('xyz'));
        $this->assertNotNull($validator->errors()->firstOf('zyx'));
        $this->assertIsString($validator->errors()->firstOf('zyx'));
    }
}
