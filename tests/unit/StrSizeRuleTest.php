<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrSize;

#[CoversClass(StrSize::class)]
class StrSizeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrSize(5);
        $this->assertTrue($rule->validate('hello'));
        $this->assertTrue($rule->validate('12345'));
    }

    public function testFails(): void
    {
        $rule = new StrSize(5);
        $this->assertFalse($rule->validate('hi'));
        $this->assertEquals('The field :{field} must contain exactly 5 characters.', $rule->error());

        $this->assertFalse($rule->validate('hello world'));
        $this->assertEquals('The field :{field} must contain exactly 5 characters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrSize(5);
        $this->assertFalse($rule->validate(12345));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }

    public function testInvalidConstructorParameterCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule [str_size] is invalid.');
        new StrSize('not-an-integer');
    }
}
