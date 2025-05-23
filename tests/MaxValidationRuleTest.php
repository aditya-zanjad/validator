<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Max;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Max::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
class MaxValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testMaxRulePasses(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'max:123457',
            'def' => 'max:0',
            'ghi' => 'max:4',
            'jkl' => 'max:1',
            'xyz' => 'max:-10',
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
    public function testMaxRuleFails(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'max:10',
            'def' => 'max:-1',
            'ghi' => 'max:2',
            'jkl' => 'max:0',
            'xyz' => 'max:-100'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());

        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertNotNull($validator->errors()->firstOf('def'));
        $this->assertNotNull($validator->errors()->firstOf('ghi'));
        $this->assertNotNull($validator->errors()->firstOf('jkl'));

        $this->assertIsString($validator->errors()->first());
        $this->assertIsString($validator->errors()->firstOf('abc'));
        $this->assertIsString($validator->errors()->firstOf('def'));
        $this->assertIsString($validator->errors()->firstOf('ghi'));
        $this->assertIsString($validator->errors()->firstOf('jkl'));
    }
}
