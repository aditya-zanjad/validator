<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrSize;

#[CoversClass(ArrSize::class)]
class ArrSizeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new ArrSize(3);
        $this->assertTrue($rule->validate([1, 2, 3]));
        $this->assertTrue($rule->validate(['a', 'b', 'c']));
    }

    public function testFails(): void
    {
        $rule = new ArrSize(3);
        $this->assertFalse($rule->validate([1, 2]));
        $this->assertEquals('The field :{field} must contain exactly 3 elements.', $rule->error());

        $this->assertFalse($rule->validate([1, 2, 3, 4]));
        $this->assertEquals('The field :{field} must contain exactly 3 elements.', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrSize(3);
        $this->assertFalse($rule->validate('string'));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testInvalidSizeParameterCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule [arr_size] is invalid.');
        new ArrSize('not-an-int');
    }
}
