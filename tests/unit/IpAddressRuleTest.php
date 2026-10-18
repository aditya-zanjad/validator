<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\IpAddress;

#[CoversClass(IpAddress::class)]
class IpAddressRuleTest extends TestCase
{
    public function testPassesForGeneralIp(): void
    {
        $rule = new IpAddress();
        $this->assertTrue($rule->validate('127.0.0.1'));
        $this->assertTrue($rule->validate('192.168.1.1'));
        $this->assertTrue($rule->validate('::1'));
        $this->assertTrue($rule->validate('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
    }

    public function testFailsForGeneralIp(): void
    {
        $rule = new IpAddress();
        $this->assertFalse($rule->validate('256.256.256.256'));
        $this->assertEquals('The field :{field} must be a valid IP address.', $rule->error());

        $this->assertFalse($rule->validate('invalid-ip'));
        $this->assertFalse($rule->validate(12345));
    }

    public function testPassesForIpv4Only(): void
    {
        $rule = new IpAddress('v4');
        $this->assertTrue($rule->validate('8.8.8.8'));
        $this->assertFalse($rule->validate('::1'));
    }

    public function testPassesForIpv6Only(): void
    {
        $rule = new IpAddress('v6');
        $this->assertTrue($rule->validate('::1'));
        $this->assertFalse($rule->validate('8.8.8.8'));
    }

    public function testInvalidOptionThrowsException(): void
    {
        $rule = new IpAddress('unsupported-option');
        $this->expectException(\Exception::class);
        $rule->validate('127.0.0.1');
    }
}
