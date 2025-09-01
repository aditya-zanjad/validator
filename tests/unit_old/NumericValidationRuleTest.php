<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Numeric::class)]
#[CoversClass(Required::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class NumericValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  123,
            'def'   =>  0.12311551,
            'xyz'   =>  -10000.24512,
            'pqr'   =>  358234.21178412,
            'ghi'   =>  '12478125.1231245757',
            'axz'   =>  '-12345',
            'mno'   =>  -124712123
        ], [
            'abc'   =>  'integer',
            'def'   =>  'numeric',
            'pqr'   =>  'numeric',
            'xyz'   =>  'numeric',
            'ghi'   =>  'numeric',
            'axz'   =>  'numeric',
            'mno'   =>  'numeric'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
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
            'abc'   =>  'abcdefghi',
            'def'   =>  '-1abc',
            'ghi'   =>  'abc',
            'jkl'   =>  ['key' => 'value'],
            'xyz'   =>  (object) [],
        ], [
            'abc'   =>  'integer',
            'def'   =>  'numeric',
            'ghi'   =>  'numeric',
            'jkl'   =>  'numeric',
            'xyz'   =>  'numeric'
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
