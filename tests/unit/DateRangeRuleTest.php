<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateRange;

#[CoversClass(DateRange::class)]
class DateRangeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new DateRange('2026-01-01', '2026-01-10');
        $this->assertTrue($rule->validate('2026-01-01')); // boundary allowed
        $this->assertTrue($rule->validate('2026-01-05'));
        $this->assertTrue($rule->validate('2026-01-10')); // boundary allowed
    }

    public function testFails(): void
    {
        $rule = new DateRange('2026-01-01', '2026-01-10');
        $this->assertFalse($rule->validate('2025-12-31'));
        $this->assertEquals('The field :{field} must be a date in the allowed date range [2026-01-01 - 2026-01-10].', $rule->error());

        $this->assertFalse($rule->validate('2026-01-11'));
        $this->assertEquals('The field :{field} must be a date in the allowed date range [2026-01-01 - 2026-01-10].', $rule->error());
    }

    public function testInvalidDateFails(): void
    {
        $rule = new DateRange('2026-01-01', '2026-01-10');
        $this->assertFalse($rule->validate('invalid'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());
    }

    public function testInvalidConstructorDatesThrowException(): void
    {
        $this->expectException(\Exception::class);
        new DateRange('invalid', '2026-01-10');
    }
}
