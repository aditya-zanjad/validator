<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateMax;

#[CoversClass(DateMax::class)]
class DateMaxRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new DateMax('2026-06-01');
        $this->assertTrue($rule->validate('2026-06-01')); // boundary allowed
        $this->assertTrue($rule->validate('2026-05-31'));
        $this->assertTrue($rule->validate('2025-01-01'));
    }

    public function testFails(): void
    {
        $rule = new DateMax('2026-06-01');
        $this->assertFalse($rule->validate('2026-06-02'));
        $this->assertEquals('The field :{field} must not be greater than the date 2026-06-01.', $rule->error());
    }

    public function testInvalidDateFails(): void
    {
        $rule = new DateMax('2026-06-01');
        $this->assertFalse($rule->validate('invalid-date'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());
    }

    public function testInvalidConstructorDateThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new DateMax('not-a-date');
    }
}
