<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Min;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Min::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
class MinValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testMinRulePasses(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'min:3',
            'def' => 'min:-1',
            'ghi' => 'min:3',
            'jkl' => 'min:1',
            'xyz' => 'min:-30',
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
    public function testMinRuleFails(): void
    {
        $validator = validate([
            'abc' => 123456,
            'def' => 0,
            'ghi' => 'abc',
            'jkl' => 'x',
            'xyz' => -20,
        ], [
            'abc' => 'min:1000000',
            'def' => 'min:10',
            'ghi' => 'min:15',
            'jkl' => 'min:25',
            'xyz' => 'min:0,1,2,3'
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
