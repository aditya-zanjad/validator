<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumMin;

#[CoversClass(NumMin::class)]
class NumMinRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumMin(10);
        $this->assertTrue($rule->validate(10));
        $this->assertTrue($rule->validate(10.5));
        $this->assertTrue($rule->validate(100));
        $this->assertTrue($rule->validate('15'));
    }

    public function testFails(): void
    {
        $rule = new NumMin(10);
        $this->assertFalse($rule->validate(9.99));
        $this->assertEquals('The field :{field} must be at least 10.', $rule->error());

        $this->assertFalse($rule->validate(-5));
        $this->assertEquals('The field :{field} must be at least 10.', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumMin(10);
        $this->assertFalse($rule->validate('invalid'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(false));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }
}
