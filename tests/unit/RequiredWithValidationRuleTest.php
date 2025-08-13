<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\RequiredWith;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredWith::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class RequiredWithValidationRuleTest extends TestCase
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
            'def'   => 123456,
            'ghi'   => 'abc_def_ghi',
            'ijk'   => ['this is a test array!'],
            'xyz'   =>  null,
        ], [
            'abc'   =>  'required_with:ijk|numeric|integer|size:123',
            'def'   =>  'required_with:ghi|numeric|integer|size:123456',
            'ghi'   =>  'required_with:ijk|string|min:9|max:12',
            'ijk'   =>  'required_with:abc,def,ghi|array|size:1',
            'pqr'   =>  'required_with:xyz|string',
            'xyz'   =>  'required_with:pqr|array|size:10'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first('abc'));
        $this->assertNull($validator->errors()->first('def'));
        $this->assertNull($validator->errors()->first('ghi'));
        $this->assertNull($validator->errors()->first('ijk'));
        $this->assertNull($validator->errors()->first('pqr'));
        $this->assertNull($validator->errors()->first('xyz'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   => null,
            'ijk'   => '123',
            'pqr'   =>  'NULL'
        ], [
            'abc'   =>  'required_with:ijk|numeric|integer|size:3',
            'def'   =>  'required_with:pqr|numeric|integer|size:123456',
            'ijk'   =>  'required_with:abc,def,ghi|string|size:1',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertIsArray($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first('abc'));
        $this->assertIsString($validator->errors()->first('abc'));
        $this->assertNotNull($validator->errors()->first('def'));
        $this->assertIsString($validator->errors()->first('def'));
        $this->assertNotNull($validator->errors()->first('ghi'));
        $this->assertIsString($validator->errors()->first('ghi'));
        $this->assertNotNull($validator->errors()->first('ijk'));
        $this->assertIsString($validator->errors()->first('ijk'));
    }
}
