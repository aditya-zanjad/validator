<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Max;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Max::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
class SizeValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testSizeRulePasses(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'size:123456',
            'def' => 'size:0',
            'ghi' => 'size:3',
            'jkl' => 'size:1',
            'xyz' => 'size:-20',
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
    public function testSizeRuleFails(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'size:10',
            'def' => 'size:-1',
            'ghi' => 'size:2',
            'jkl' => 'size:0',
            'xyz' => 'size:-100'
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
