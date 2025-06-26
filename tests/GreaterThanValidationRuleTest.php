<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Gt;
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
#[CoversClass(Gt::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
class GreaterThanValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testGreaterThanValidationRulePasses(): void
    {
        $validator = validate([
          'abc' =>  'c',
          'def' =>  '120',
          'ghi' =>  '1',
          'jkl' =>  0
        ], [
            'abc'   =>  'required|string|gt:b',
            'def'   =>  'integer|gt:100',
            'ghi'   =>  'numeric|integer|gt:0',
            'jkl'   =>  'lt:1|gt:-1'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testGreaterThanValidationRuleFails(): void
    {
        $validator = validate([
          'abc' =>  'a',
          'def' =>  '100',
          'ghi' =>  '-1',
          'jkl' =>  -100
        ], [
            'abc'   =>  'required|string|gt:b',
            'def'   =>  'integer|gt:100',
            'ghi'   =>  'numeric|integer|gt:0',
            'jkl'   =>  'lt:1|gt:-1'
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
