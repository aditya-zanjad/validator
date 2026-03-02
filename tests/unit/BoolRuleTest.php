<?php

// declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Boolean;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Boolean::class)]
#[CoversClass(AbstractRule::class)]
final class BoolRuleTest extends TestCase
{
    public function testPasses()
    {
        $booleans = [
            0,
            1,
            true,
            false,
            '0',
            '1',
            'on',
            'off',
            'yes',
            'no',
        ];

        foreach ($booleans as $boolean) {
            $rule = new Boolean();
            $this->assertTrue($rule->validate($boolean));
        }
    }

    public function testFails()
    {
        $invalidBooleans = [
            "This is definitely not a number!",
            "123abc",
            "abc456",
            ["This is also not a valid number"],
            (object) ["This will evaluate into an object"],
            123.03,
            -1234.01,
            -1,
            '5478',
            '7890',
            '-789.1024',
            '',
        ];

        foreach ($invalidBooleans as $invalidBoolean) {
            $rule = new Boolean();
            $this->assertFalse($rule->validate($invalidBoolean));
        }
    }
}
