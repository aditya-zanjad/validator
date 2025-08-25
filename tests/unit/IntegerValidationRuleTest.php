<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\TypeInteger;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(TypeInteger::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class IntegerValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc' => 123,
            'def' => 0,
            'xyz' => 10000,
            'pqr' => [
                123 => [
                    456 => [
                        789 => [
                            123 => 358234
                        ]
                    ]
                ]
            ],
            'ghi' => '12478125',
            'axz' => '-12345',
            'mno' => -124712123
        ], [
            'abc'                   =>  'integer',
            'def'                   =>  'integer',
            'pqr.123.456.789.123'   =>  'integer',
            'xyz'                   =>  'integer',
            'ghi'                   =>  'integer',
            'axz'                   =>  'integer',
            'mno'                   =>  'integer'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('pqr.123.456.789.123'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('axz'));
        $this->assertNull($validator->errors()->firstOf('mno'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc' => 'abcdefghi',
            'def' => '-1abc',
            'ghi' => 'abc',
            'jkl' => ['key' => 'value'],
            'xyz' => (object) [],
        ], [
            'abc' => 'integer',
            'def' => 'integer',
            'ghi' => 'integer',
            'jkl' => 'integer',
            'xyz' => 'integer'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertNotNull($validator->errors()->firstOf('def'));
        $this->assertNotNull($validator->errors()->firstOf('ghi'));
        $this->assertNotNull($validator->errors()->firstOf('jkl'));
    }
}
