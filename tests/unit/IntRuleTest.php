<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Integer;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Integer::class)]
#[CoversClass(AbstractRule::class)]
final class IntRuleTest extends TestCase
{
    public function testPasses()
    {
        $numbers = [0, -1, 1, -999, 999, "123", "456"];

        foreach ($numbers as $number) {
            $rule = new Integer();
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
            123.03,
            -1234.01,
            '-789.1024',
            '',
        ];

        foreach ($invalidNumbers as $invalidNumber) {
            $rule = new Integer();
            $this->assertFalse($rule->validate($invalidNumber));
        }
    }
}
