<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\MacAddress;

#[CoversClass(MacAddress::class)]
class MacAddressRuleTest extends TestCase
{
    public function testPassesForValidMacAddress(): void
    {
        $rule = new MacAddress();
        $this->assertTrue($rule->validate('00:1A:2B:3C:4D:5E'));
        $this->assertTrue($rule->validate('00-1A-2B-3C-4D-5E'));
        $this->assertTrue($rule->validate('001a.2b3c.4d5e'));
    }

    public function testFailsForInvalidMacAddress(): void
    {
        $rule = new MacAddress();
        $this->assertFalse($rule->validate('00:1A:2B:3C:4D:GG'));
        $this->assertEquals('The field :{field} must be a valid MAC address.', $rule->error());

        $this->assertFalse($rule->validate('not-a-mac'));
        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
    }
}
