<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrGreaterThan;

#[CoversClass(StrGreaterThan::class)]
class StrGreaterThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrGreaterThan(3);
        $this->assertTrue($rule->validate('abcd'));
        $this->assertTrue($rule->validate('hello world'));
    }

    public function testFails(): void
    {
        $rule = new StrGreaterThan(3);
        $this->assertFalse($rule->validate('abc'));
        $this->assertEquals('The field :{field} must contain more than 3 characters.', $rule->error());

        $this->assertFalse($rule->validate('ab'));
        $this->assertFalse($rule->validate(''));
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrGreaterThan(3);
        $this->assertFalse($rule->validate(12345));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }
}
