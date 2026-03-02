<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Number;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Number::class)]
#[CoversClass(AbstractRule::class)]
final class NumRuleTest extends TestCase
{
    public function testPasses()
    {
        $numbers = [0, -1, 1, -999.999, 999.999, "123", "456.789"];

        foreach ($numbers as $number) {
            $rule = new Number();
            $this->assertTrue($rule->validate($number));
        }
    }

    public function testFails()
    {
        $invalidNumbers = [
            "This is definitely not a number!",
            "123abc",
            "abc456",
            ["This is also not a valid number"],
            (object) ["This will evaluate into an object"],
            false,
            true,
        ];

        foreach ($invalidNumbers as $invalidNumber) {
            $rule = new Number();
            $this->assertFalse($rule->validate($invalidNumber));
        }
    }
}
