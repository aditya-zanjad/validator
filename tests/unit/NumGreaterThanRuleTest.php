<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumGreaterThan;

#[CoversClass(NumGreaterThan::class)]
class NumGreaterThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumGreaterThan(10);
        $this->assertTrue($rule->validate(11));
        $this->assertTrue($rule->validate(10.01));
        $this->assertTrue($rule->validate('15'));
    }

    public function testFails(): void
    {
        $rule = new NumGreaterThan(10);
        $this->assertFalse($rule->validate(10));
        $this->assertEquals('The field :{field} must be greater than 10.', $rule->error());

        $this->assertFalse($rule->validate(9.99));
        $this->assertEquals('The field :{field} must be greater than 10.', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumGreaterThan(10);
        $this->assertFalse($rule->validate('string'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(true));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }
}
