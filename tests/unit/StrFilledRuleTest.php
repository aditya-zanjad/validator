<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrFilled;

#[CoversClass(StrFilled::class)]
class StrFilledRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrFilled();
        $this->assertTrue($rule->validate('hello'));
        $this->assertTrue($rule->validate(' '));
        $this->assertTrue($rule->validate('0'));
    }

    public function testFails(): void
    {
        $rule = new StrFilled();
        $this->assertFalse($rule->validate(''));
        $this->assertEquals('The field :{field} must be filled.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrFilled();
        $this->assertFalse($rule->validate(null));
        $this->assertFalse($rule->validate(123));
        $this->assertFalse($rule->validate(['hello']));
        $this->assertEquals('The field :{field} must be filled.', $rule->error());
    }
}
