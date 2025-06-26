<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Gte;
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
#[CoversClass(Gte::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
class GreaterThanOrEqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testGreaterThanOrEqualValidationRulePasses(): void
    {
        $validator = validate([
          'abc' =>  'b',
          'def' =>  '101',
          'ghi' =>  1,
          'jkl' =>  0
        ], [
            'abc'   =>  'required|string|gte:b',
            'def'   =>  'integer|gte:100',
            'ghi'   =>  'numeric|integer|gte:-100',
            'jkl'   =>  'lt:1|gte:-1'
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
    public function testGreaterThanOrEqualValidationRuleFails(): void
    {
        $validator = validate([
          'abc' =>  'a',
          'def' =>  '100',
          'ghi' =>  '-1',
          'jkl' =>  -100
        ], [
            'abc'   =>  'required|string|gte:b',
            'def'   =>  'integer|gt:101',
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
