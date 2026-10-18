<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumLessThan;

#[CoversClass(NumLessThan::class)]
class NumLessThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumLessThan(10);
        $this->assertTrue($rule->validate(9));
        $this->assertTrue($rule->validate(9.99));
        $this->assertTrue($rule->validate('5'));
        $this->assertTrue($rule->validate(-10));
    }

    public function testFails(): void
    {
        $rule = new NumLessThan(10);
        $this->assertFalse($rule->validate(10));
        $this->assertEquals('The field :{field} must be less than 10.', $rule->error());

        $this->assertFalse($rule->validate(10.5));
        $this->assertEquals('The field :{field} must be less than 10.', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumLessThan(10);
        $this->assertFalse($rule->validate('not-a-number'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(false));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }
}
