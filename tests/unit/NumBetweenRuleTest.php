<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumBetween;

#[CoversClass(NumBetween::class)]
class NumBetweenRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumBetween(10, 20);
        $this->assertTrue($rule->validate(11));
        $this->assertTrue($rule->validate(19.9));
        $this->assertTrue($rule->validate('15'));
    }

    public function testFails(): void
    {
        $rule = new NumBetween(10, 20);
        $this->assertFalse($rule->validate(10)); // exclusive
        $this->assertEquals('The field :{field} must be between the values [10 - 20]', $rule->error());

        $this->assertFalse($rule->validate(20)); // exclusive
        $this->assertEquals('The field :{field} must be between the values [10 - 20]', $rule->error());

        $this->assertFalse($rule->validate(5));
        $this->assertFalse($rule->validate(25));
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumBetween(10, 20);
        $this->assertFalse($rule->validate('not-a-number'));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());

        $this->assertFalse($rule->validate(true));
        $this->assertEquals('The field :{field} must be a valid number.', $rule->error());
    }
}
