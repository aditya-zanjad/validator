<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrMax;

#[CoversClass(ArrMax::class)]
class ArrMaxRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new ArrMax(3);
        $this->assertTrue($rule->validate([]));
        $this->assertTrue($rule->validate([1]));
        $this->assertTrue($rule->validate([1, 2]));
        $this->assertTrue($rule->validate([1, 2, 3]));
    }

    public function testFails(): void
    {
        $rule = new ArrMax(3);
        $this->assertFalse($rule->validate([1, 2, 3, 4]));
        $this->assertEquals('The field :{field} must not contain more than 3 elements.', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrMax(3);
        $this->assertFalse($rule->validate('string'));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testNegativeLimitCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule arr_max [AdityaZanjad\Validator\Rules\ArrMax] must be valid.');
        new ArrMax(-1);
    }
}
