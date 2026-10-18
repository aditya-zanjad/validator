<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumMax;

#[CoversClass(NumMax::class)]
class NumMaxRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumMax(100);
        $this->assertTrue($rule->validate(100));
        $this->assertTrue($rule->validate(99));
        $this->assertTrue($rule->validate('50'));
        $this->assertTrue($rule->validate(-500));
    }

    public function testFails(): void
    {
        $rule = new NumMax(100);
        $this->assertFalse($rule->validate(100.01));
        $this->assertEquals('The field :{field} must not be greater than 100.', $rule->error());

        $this->assertFalse($rule->validate(101));
        $this->assertEquals('The field :{field} must not be greater than 100.', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumMax(100);
        $this->assertFalse($rule->validate('string'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(true));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }
}
