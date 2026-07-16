<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\ArrGreaterThan;

#[CoversClass(ArrGreaterThan::class)]
final class ArrGreaterThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $arr = [1, 2, 3, 4, 5];

        $rule   =   new ArrGreaterThan(3);
        $result =   $rule->validate($arr);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testFails(): void
    {
        $arrs = [
            [],
            [1, 2],
            [1, 2, 3]
        ];

        foreach ($arrs as $arr) {
            $rule   =   new ArrGreaterThan(3);
            $result =   $rule->validate($arr);

            $this->assertIsBool($result);
            $this->assertFalse($result);
        }
    }
}