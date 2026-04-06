<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\ArrBetween;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ArrBetween::class)]
class ArrBetweenRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $arrs = [
            [1, 2, 3, 4],
            [4, 'abcded', 'a' => 'bcd', 5],
            [4, 4 => [5, 2 => [3 => [5]]], 7, 8]
        ];

        foreach ($arrs as $arr) {
            $rule = new ArrBetween(3, 5);
            $this->assertTrue($rule->validate($arr));
        }
    }

    public function testFails(): void
    {
        $rule = new ArrBetween(2, 3);
        $this->assertFalse($rule->validate(['a', 'b', 'c', 'd']));
        $this->assertIsString($rule->error(), 'The field :{field} must contain elements between the limit 2 to 3.');
    }

    public function testMinLengthLessThanZeroCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter [min_length] supplied to the validation rule [arr_between] must be valid.');
        $rule = new ArrBetween(-1, 10);
    }

    public function testMaxLengthLessThanZeroCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter [max_length] supplied to the validation rule [arr_between] must be valid.');
        $rule = new ArrBetween(10, -1000);
    }

    public function testMaxLengthLessThanOrEqualToMinLengthCausesException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter [max_length] supplied to the validation rule [arr_between] must be greater than the parameter [max_length].');
        $rule = new ArrBetween(10, 10);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter [max_length] supplied to the validation rule [arr_between] must be greater than the parameter [max_length].');
        $rule = new ArrBetween(10, 2);
    }

    public function testInvalidArrayFailsValidation(): void
    {
        $rule = new ArrBetween(1, 100);
        $this->assertFalse($rule->validate(12));
        $this->assertIsString($rule->error(), 'The field :{field} must be an array.');
    }
}
