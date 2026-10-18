<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumRange;

#[CoversClass(NumRange::class)]
class NumRangeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumRange(10, 20);
        $this->assertTrue($rule->validate(10));
        $this->assertTrue($rule->validate(15));
        $this->assertTrue($rule->validate(20));
        $this->assertTrue($rule->validate('15.5'));
    }

    public function testFails(): void
    {
        $rule = new NumRange(10, 20);
        $this->assertFalse($rule->validate(9.99));
        $this->assertEquals('The field :{field} must be in the range [10 - 20]', $rule->error());

        $this->assertFalse($rule->validate(20.01));
        $this->assertEquals('The field :{field} must be in the range [10 - 20]', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumRange(10, 20);
        $this->assertFalse($rule->validate('abc'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(true));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }

    public function testInvalidConstructorArgumentsCauseException(): void
    {
        $this->expectException(\Exception::class);
        new NumRange('invalid', 20);
    }

    public function testMinGreaterThanMaxCausesException(): void
    {
        $this->expectException(\Exception::class);
        new NumRange(30, 20);
    }
}
