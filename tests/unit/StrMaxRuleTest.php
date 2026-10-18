<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrMax;

#[CoversClass(StrMax::class)]
class StrMaxRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrMax(5);
        $this->assertTrue($rule->validate(''));
        $this->assertTrue($rule->validate('a'));
        $this->assertTrue($rule->validate('hello'));
    }

    public function testFails(): void
    {
        $rule = new StrMax(5);
        $this->assertFalse($rule->validate('hello world'));
        $this->assertEquals('The field :{field} must not contain more than 5 characters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrMax(5);
        $this->assertFalse($rule->validate(123456));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }
}
