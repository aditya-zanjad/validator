<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\NumEq;

#[CoversClass(NumEq::class)]
class NumEqRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new NumEq(42);
        $this->assertTrue($rule->validate(42));
        $this->assertTrue($rule->validate('42'));

        $floatRule = new NumEq(3.14);
        $this->assertTrue($floatRule->validate(3.14));
        $this->assertTrue($floatRule->validate('3.14'));
    }

    public function testFails(): void
    {
        $rule = new NumEq(42);
        $this->assertFalse($rule->validate(43));
        $this->assertEquals('The field :{field} must be exactly equal to 42', $rule->error());
    }

    public function testInvalidNumberFailsValidation(): void
    {
        $rule = new NumEq(42);
        $this->assertFalse($rule->validate('invalid'));
        $this->assertEquals('The field :{field} must be a valid number', $rule->error());

        $this->assertFalse($rule->validate(false));
        $this->assertEquals('The field :{field} must be a valid number', $rule->error());
    }

    public function testInvalidConstructorParameterCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule num_eq is invalid.');
        new NumEq('abc');
    }
}
