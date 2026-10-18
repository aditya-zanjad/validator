<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrMin;

#[CoversClass(ArrMin::class)]
class ArrMinRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new ArrMin(2);
        $this->assertTrue($rule->validate([1, 2]));
        $this->assertTrue($rule->validate([1, 2, 3]));
    }

    public function testFails(): void
    {
        $rule = new ArrMin(2);
        $this->assertFalse($rule->validate([]));
        $this->assertEquals('The field :{field} must contain at least 2 elements.', $rule->error());

        $this->assertFalse($rule->validate([1]));
        $this->assertEquals('The field :{field} must contain at least 2 elements.', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrMin(2);
        $this->assertFalse($rule->validate(123));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testNegativeLimitCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule arr_min [AdityaZanjad\Validator\Rules\ArrMin] must be valid.');
        new ArrMin(-1);
    }
}
