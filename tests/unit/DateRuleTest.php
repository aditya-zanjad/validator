<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Date;

#[CoversClass(Date::class)]
class DateRuleTest extends TestCase
{
    public function testValidDatesPass(): void
    {
        $rule = new Date();
        $this->assertTrue($rule->validate('2026-10-18'));
        $this->assertTrue($rule->validate('2026-10-18 22:00:00'));
        $this->assertTrue($rule->validate('18-10-2026'));
        $this->assertTrue($rule->validate('today'));
        $this->assertTrue($rule->validate('next Monday'));
    }

    public function testInvalidDatesFail(): void
    {
        $rule = new Date();
        $this->assertFalse($rule->validate('not-a-date'));
        $this->assertEquals('The field :{field} must be a valid date.', $rule->error());

        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
        $this->assertFalse($rule->validate([]));
    }

    public function testDateWithSpecificFormatPasses(): void
    {
        $rule = new Date('Y-m-d');
        $this->assertTrue($rule->validate('2026-10-18'));
    }

    public function testDateWithSpecificFormatFails(): void
    {
        $rule = new Date('Y-m-d');
        $this->assertFalse($rule->validate('18/10/2026'));
        $this->assertEquals('The field :{field} must be a valid date with the format Y-m-d', $rule->error());
    }
}
