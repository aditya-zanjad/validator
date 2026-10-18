<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\DateLessThan;

#[CoversClass(DateLessThan::class)]
class DateLessThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new DateLessThan('2026-06-01');
        $this->assertTrue($rule->validate('2026-05-31'));
        $this->assertTrue($rule->validate('2025-01-01'));
    }

    public function testFails(): void
    {
        $rule = new DateLessThan('2026-06-01');
        $this->assertFalse($rule->validate('2026-06-01'));
        $this->assertEquals('The field :{field} must be a date less than the date 2026-06-01.', $rule->error());

        $this->assertFalse($rule->validate('2026-06-02'));
        $this->assertEquals('The field :{field} must be a date less than the date 2026-06-01.', $rule->error());
    }

    public function testInvalidDateFails(): void
    {
        $rule = new DateLessThan('2026-06-01');
        $this->assertFalse($rule->validate('invalid'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());
    }

    public function testInvalidConstructorDateThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new DateLessThan('not-a-date');
    }
}
