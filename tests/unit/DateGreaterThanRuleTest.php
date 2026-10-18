<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateGreaterThan;

#[CoversClass(DateGreaterThan::class)]
class DateGreaterThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new DateGreaterThan('2026-01-01');
        $this->assertTrue($rule->validate('2026-01-02'));
        $this->assertTrue($rule->validate('2026-01-01 00:00:01'));
        $this->assertTrue($rule->validate('2027-01-01'));
    }

    public function testFails(): void
    {
        $rule = new DateGreaterThan('2026-01-01');
        $this->assertFalse($rule->validate('2026-01-01'));
        $this->assertEquals('The field :{field} must be a date greater than the date 2026-01-01.', $rule->error());

        $this->assertFalse($rule->validate('2025-12-31'));
        $this->assertEquals('The field :{field} must be a date greater than the date 2026-01-01.', $rule->error());
    }

    public function testInvalidDateFails(): void
    {
        $rule = new DateGreaterThan('2026-01-01');
        $this->assertFalse($rule->validate('not-a-date'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());
    }

    public function testInvalidConstructorDateThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new DateGreaterThan('invalid-date');
    }
}
