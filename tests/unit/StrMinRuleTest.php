<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrMin;

#[CoversClass(StrMin::class)]
class StrMinRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrMin(3);
        $this->assertTrue($rule->validate('abc'));
        $this->assertTrue($rule->validate('abcd'));
    }

    public function testFails(): void
    {
        $rule = new StrMin(3);
        $this->assertFalse($rule->validate('ab'));
        $this->assertEquals('The field :{field} must contain at least 3 characters.', $rule->error());

        $this->assertFalse($rule->validate(''));
        $this->assertEquals('The field :{field} must contain at least 3 characters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrMin(3);
        $this->assertFalse($rule->validate(1234));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }

    public function testInvalidConstructorParameterCausesException(): void
    {
        $this->expectException(\Exception::class);
        new StrMin('invalid');
    }

    public function testNegativeLimitCausesException(): void
    {
        $this->expectException(\Exception::class);
        new StrMin(-1);
    }
}
