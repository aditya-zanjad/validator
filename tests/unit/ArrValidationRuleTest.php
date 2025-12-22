<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Arr;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Arr::class)]
#[CoversClass(AbstractRule::class)]
final class ArrValidationRuleTest extends TestCase
{
    public function testPasses()
    {
        $arrs = [
            [1, 2, 3, 4, 5, 6],
            [],
        ];

        foreach ($arrs as $arr) {
            $rule = new Arr();
            $this->assertTrue($rule->validate($arr));
        }
    }

    public function testFails()
    {
        $arrs = [
            123,
            123.123,
            true,
            'this is definitely not an array',
            (object) []
        ];

        foreach ($arrs as $arr) {
            $rule = new Arr();
            $this->assertFalse($rule->validate($arr));
            $this->assertEquals($rule->error(), 'The field :{field} must be an array');
        }
    }
}
