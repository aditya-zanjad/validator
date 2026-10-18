<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateBetween;

#[CoversClass(DateBetween::class)]
class DateBetweenRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new DateBetween('2026-01-01', '2026-01-10');
        $this->assertTrue($rule->validate('2026-01-05'));
        $this->assertTrue($rule->validate('2026-01-09 23:59:59'));
    }

    public function testFails(): void
    {
        $rule = new DateBetween('2026-01-01', '2026-01-10');
        $this->assertFalse($rule->validate('2026-01-01')); // boundary (exclusive)
        $this->assertEquals('The field :{field} must be a date between the dates [2026-01-01 - 2026-01-10].', $rule->error());

        $this->assertFalse($rule->validate('2026-01-10')); // boundary (exclusive)
        $this->assertEquals('The field :{field} must be a date between the dates [2026-01-01 - 2026-01-10].', $rule->error());

        $this->assertFalse($rule->validate('2025-12-31'));
        $this->assertFalse($rule->validate('2026-01-15'));
    }

    public function testInvalidDateValueFails(): void
    {
        $rule = new DateBetween('2026-01-01', '2026-01-10');
        $this->assertFalse($rule->validate('invalid-date'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());
    }

    public function testInvalidConstructorDatesThrowException(): void
    {
        $this->expectException(\Exception::class);
        new DateBetween('invalid-date', '2026-01-10');
    }

    public function testMaxDateBeforeMinDateThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new DateBetween('2026-01-10', '2026-01-01');
    }
}
