<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class RequiredValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation fails when the required field is missing.
     *
     * @return void
     */
    public function testRequiredFieldMissing(): void
    {
        $validator = validate([], ['abc' => 'required']);
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->all());
    }

    /**
     * Test that the validator fails when the required field is set to null.
     *
     * @return void
     */
    public function testRequiredFieldIsNull(): void
    {
        $validator = validate(['abc' => null,], ['abc' => 'required']);
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->all());
    }

    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testRequiredFieldsArePresent(): void
    {
        $validator = validate([
            'abc'   =>  [],
            'xyz'   =>  '',
            'pqr'   =>  false,
            123     =>  0,
            456     =>  '0',
            789     =>  'false',
        ], [
            'abc'   =>  'required',
            'xyz'   =>  'required',
            'pqr'   =>  'required',
            123     =>  'required',
            456     =>  'required',
            789     =>  'required',
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first('abc'));
        $this->assertNull($validator->errors()->first('xyz'));
        $this->assertNull($validator->errors()->first('123'));
        $this->assertNull($validator->errors()->first('456'));
        $this->assertNull($validator->errors()->first('abc'));
        $this->assertNull($validator->errors()->first('abc'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testDeepNestedRequiredFieldsAreMissing(): void
    {
        $validator = validate([
            'abc' => [
                'pqr' => [
                    //
                ]
            ],
            123 => [
                456 => [
                    789 => [
                        0 => null
                    ]
                ]
            ]
        ], [
            'abc.pqr.xyz'   =>  'required',
            '123.456.789.0' =>  'required'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotNull($validator->errors()->firstOf('abc.pqr.xyz'));
        $this->assertNotNull($validator->errors()->firstOf('123.456.789.0'));
        $this->assertNotEmpty($validator->errors()->all());
    }

    /**
     * Assert that the validation succeeds when the required fields are present.
     *
     * @return void
     */
    public function testDeepNestedRequiredFieldsArePresent(): void
    {
        $validator = validate([
            'abc' => [
                'def' => [
                    'ghi' => [
                        'jkl' => [
                            'mno' => [
                                'pqr' => [
                                    'uvw' => [
                                        'xyz' => []
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            123 => [
                456 => [
                    789 => [
                        0 => 0
                    ]
                ]
            ]
        ], [
            'abc.def.ghi.jkl.mno.pqr.uvw.xyz'   =>  'required',
            '123.456.789.0'                     =>  'required'
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('123.456.789.0'));
        $this->assertNull($validator->errors()->firstOf('abc.def.ghi.jkl.mno.pqr.uvw.xyz'));
    }
}
