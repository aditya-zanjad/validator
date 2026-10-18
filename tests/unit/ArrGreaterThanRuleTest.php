<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrGreaterThan;

#[CoversClass(ArrGreaterThan::class)]
class ArrGreaterThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $arrs = [
            [1, 2, 3],
            ['a', 'b', 'c', 'd'],
            [1, 2, 3, 4, 5, 6, 7]
        ];

        foreach ($arrs as $arr) {
            $rule = new ArrGreaterThan(2);
            $this->assertTrue($rule->validate($arr));
        }
    }

    public function testFails(): void
    {
        $rule = new ArrGreaterThan(2);
        $this->assertFalse($rule->validate(['a', 'b']));
        $this->assertEquals('The field :{field} must contain more than 2 elements.', $rule->error());

        $this->assertFalse($rule->validate(['a']));
        $this->assertEquals('The field :{field} must contain more than 2 elements.', $rule->error());
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrGreaterThan(2);
        $this->assertFalse($rule->validate(123));
        $this->assertEquals('The field :{field} must be an array.', $rule->error());
    }

    public function testMinLengthLessThanZeroCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter supplied to the validation rule arr_gt [AdityaZanjad\Validator\Rules\ArrGreaterThan] must be valid.');
        new ArrGreaterThan(-1);
    }
}
