<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrLessThan;

#[CoversClass(ArrLessThan::class)]
class ArrLessThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new ArrLessThan(3);
        $this->assertTrue($rule->validate([]));
        $this->assertTrue($rule->validate([1]));
        $this->assertTrue($rule->validate([1, 2]));
    }

    public function testFails(): void
    {
        $rule = new ArrLessThan(3);
        $this->assertFalse($rule->validate([1, 2, 3]));
        $this->assertEquals('The field :{field} must contain less than 3 elements.', $rule->error());

        $this->assertFalse($rule->validate([1, 2, 3, 4]));
        $this->assertEquals('The field :{field} must contain less than 3 elements.', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrLessThan(3);
        $this->assertFalse($rule->validate('not an array'));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testNegativeLimitCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule arr_lt [AdityaZanjad\Validator\Rules\ArrLessThan] must be valid.');
        new ArrLessThan(-1);
    }
}
