<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\LessThan;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(LessThan::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
class LessThanValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testLessThanValidationRulePasses(): void
    {
        $validator = validate([
           'b' => 'b',
           123 => 123,
           0 => 0,
           -12345 => -12345
        ], [
            'b'     =>  'lt:c',
           '123'    =>  'lt:124',
           0        =>  'lt:1',
           -12345   =>  'lt:0'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('b'));
        $this->assertNull($validator->errors()->firstOf('123'));
        $this->assertNull($validator->errors()->firstOf('0'));
        $this->assertNull($validator->errors()->firstOf('-12345'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testLessThanValidationRuleFails(): void
    {
        $validator = validate([
           'b' => 'b',
           123 => 123,
           0 => 0,
           -12345 => -12345
        ], [
            'b'     =>  'lt:a',
           '123'    =>  'lt:120',
           0        =>  'lt:0',
           -12345   =>  'lt:-12345'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('b'));
        $this->assertNotNull($validator->errors()->firstOf('123'));
        $this->assertNotNull($validator->errors()->firstOf('0'));
        $this->assertNotNull($validator->errors()->firstOf('-12345'));
    }
}
