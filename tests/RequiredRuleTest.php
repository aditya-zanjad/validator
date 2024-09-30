<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Constraints\Required;

#[UsesClass(Validator::class)]
#[CoversClass(Required::class)]
#[CoversClass(Validator::class)]
final class RequiredRuleTest extends TestCase
{
    /**
     * Assert that the validation fails when the required field is missing.
     *
     * @return void
     */
    public function testRequiredFieldMissing(): void
    {
        $validator = new Validator([
            // No Input provided.
        ], [
            'abc' => 'required'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->firstErrorOf('abc'));
        $this->assertNotEmpty($validator->allErrors());
    }

    /**
     * Test that the validator fails when the required field is set to null.
     *
     * @return void
     */
    public function testRequiredFieldIsNull(): void
    {
        $validator = new Validator([
            'abc' => null
        ], [
            'abc' => 'required'
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->firstErrorOf('abc'));
        $this->assertNotEmpty($validator->allErrors());
    }

    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testRequiredFieldsArePresent(): void
    {
        $validator = new Validator([
            'abc'   =>  [],
            'xyz'   =>  '',
            'pqr'   =>  false,
            123     =>  0,
            456     =>  '0',
            789     =>  'false'
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
        $this->assertEmpty($validator->allErrors());
        $this->assertNull($validator->firstError('abc'));
        $this->assertNull($validator->firstError('xyz'));
        $this->assertNull($validator->firstError('123'));
        $this->assertNull($validator->firstError('456'));
        $this->assertNull($validator->firstError('abc'));
        $this->assertNull($validator->firstError('abc'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testDeepNestedRequiredFieldsAreMissing(): void
    {
        $validator = new Validator([
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
        $this->assertNotNull($validator->firstErrorOf('abc.pqr.xyz'));
        $this->assertNotNull($validator->firstErrorOf('123.456.789.0'));
        $this->assertNotEmpty($validator->allErrors());
    }

    /**
     * Assert that the validation succeeds when the required fields are present.
     *
     * @return void
     */
    public function testDeepNestedRequiredFieldsArePresent(): void
    {
        $validator = new Validator([
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
        $this->assertEmpty($validator->allErrors());
        $this->assertNull($validator->firstErrorOf('123.456.789.0'));
        $this->assertNull($validator->firstErrorOf('abc.def.ghi.jkl.mno.pqr.uvw.xyz'));
    }
}
