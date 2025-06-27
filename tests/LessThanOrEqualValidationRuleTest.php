<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Lte;
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
#[CoversClass(Lte::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
class LessThanOrEqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testLessThanOrEqualValidationRulePasses(): void
    {
        $validator = validate([
          'abc' =>  '3',
          'def' =>  99,
          'ghi' =>  '-1',
          'jkl' =>  -100
        ], [
            'abc'   =>  'required|string|lte:4',
            'def'   =>  'integer|lte:100',
            'ghi'   =>  'numeric|integer|lte:0',
            'jkl'   =>  'lte:1'
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
    public function testLessThanOrEqualValidationRuleFails(): void
    {
        $validator = validate([
          'abc' =>  'Hello World!',
          'def' =>  101,
          'ghi' =>  '1',
          'jkl' =>  12341351
        ], [
            'abc'   =>  'required|string|lte:3',
            'def'   =>  'integer|lte:100',
            'ghi'   =>  'numeric|integer|lte:0',
            'jkl'   =>  'lte:1'
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
