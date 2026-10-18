<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrRegex;

#[CoversClass(StrRegex::class)]
class StrRegexRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrRegex('/^[a-z]+$/');
        $this->assertTrue($rule->validate('hello'));
    }

    public function testFails(): void
    {
        $rule = new StrRegex('/^[a-z]+$/');
        $this->assertFalse($rule->validate('Hello123'));
        $this->assertEquals('The field :{field} must match with the regex pattern /^[a-z]+$/', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrRegex('/^[0-9]+$/');
        $this->assertFalse($rule->validate(12345));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }

    public function testEmptyRegexThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new StrRegex('');
    }

    public function testMalformedRegexThrowsException(): void
    {
        $rule = new StrRegex('/[unclosed-regex');
        $this->expectException(\Exception::class);
        @$rule->validate('test');
    }
}
