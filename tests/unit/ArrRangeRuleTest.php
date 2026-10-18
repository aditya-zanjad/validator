<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrRange;

#[CoversClass(ArrRange::class)]
class ArrRangeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new ArrRange(2, 4);
        $this->assertTrue($rule->validate([1, 2]));
        $this->assertTrue($rule->validate([1, 2, 3]));
        $this->assertTrue($rule->validate([1, 2, 3, 4]));
    }

    public function testFails(): void
    {
        $rule = new ArrRange(2, 4);
        $this->assertFalse($rule->validate([1]));
        $this->assertEquals('The field :{field} must contain number of elements in the range [2 - 4].', $rule->error());

        $this->assertFalse($rule->validate([1, 2, 3, 4, 5]));
        $this->assertEquals('The field :{field} must contain number of elements in the range [2 - 4].', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrRange(2, 4);
        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testInvalidArgumentsCauseException(): void
    {
        $this->expectException(\Exception::class);
        new ArrRange(-1, 5);
    }

    public function testMinGreaterThanOrEqualToMaxCausesException(): void
    {
        $this->expectException(\Exception::class);
        new ArrRange(5, 2);
    }
}
