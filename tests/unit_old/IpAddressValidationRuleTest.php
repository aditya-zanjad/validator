<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\IpAddress;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(IpAddress::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class IpAddressValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'ip_1'          =>  '192.168.1.1',
            'ip_2'          =>  '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
            'ip_3'          =>  '172.217.164.164', // A public IP address,
            'ipv4'          =>  '192.168.1.1',
            'ipv6'          =>  '2001:0db8:85a3:0000:0000:8a2e:0370:7334',
            'no_private'    =>  '203.0.113.1',
            'no_reserved'   =>  '192.0.2.1',
            'public_ipv4'   =>  '8.8.8.8'
        ], [
            'ip_1'          =>  'ip',
            'ip_2'          =>  'ip',
            'ip_3'          =>  'ip:public',
            'ipv4'          =>  'ip:v4',
            'ipv6'          =>  'ip:v6',
            'no_private'    =>  'ip:public',
            'public_ipv4'   =>  'ip:v4,public,unreserved'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('ip_1'));
        $this->assertNull($validator->errors()->firstOf('ip_2'));
        $this->assertNull($validator->errors()->firstOf('ip_3'));
        $this->assertNull($validator->errors()->firstOf('ipv4'));
        $this->assertNull($validator->errors()->firstOf('ipv6'));
        $this->assertNull($validator->errors()->firstOf('no_private'));
        $this->assertNull($validator->errors()->firstOf('public_ipv4'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'ip_1'          =>  '999.999.999.999', // Invalid IPv4 format
            'ip_2'          =>  '10.0.0.1', // Private IP, but 'ip:public' is expected
            'ip_3'          =>  '2001:0db8::8a2e:0370:7334:invalid', // Invalid IPv6 format
            'ipv4'          =>  '2001:0db8::', // Not IPv4
            'ipv6'          =>  '192.168.1.1', // Not IPv6
            'no_private'    =>  '10.0.0.1', // Is a private IP
            'no_reserved'   =>  '169.254.0.1', // Is a reserved IP
            'public_ipv4'   =>  '192.168.1.1' // Is a private IP
        ], [
            'ip_1'          =>  'ip',
            'ip_2'          =>  'ip:public',
            'ip_3'          =>  'ip',
            'ipv4'          =>  'ip:v4',
            'ipv6'          =>  'ip:v6',
            'no_private'    =>  'ip:public',
            'public_ipv4'   =>  'ip:v4,public,unreserved'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotNull($validator->errors()->firstOf('ip_1'));
        $this->assertIsString($validator->errors()->firstOf('ip_1'));
        $this->assertNotNull($validator->errors()->firstOf('ip_2'));
        $this->assertIsString($validator->errors()->firstOf('ip_2'));
        $this->assertNotNull($validator->errors()->firstOf('ip_3'));
        $this->assertIsString($validator->errors()->firstOf('ip_3'));
        $this->assertNotNull($validator->errors()->firstOf('ipv4'));
        $this->assertIsString($validator->errors()->firstOf('ipv4'));
        $this->assertNotNull($validator->errors()->firstOf('ipv6'));
        $this->assertIsString($validator->errors()->firstOf('ipv6'));
        $this->assertNotNull($validator->errors()->firstOf('no_private'));
        $this->assertIsString($validator->errors()->firstOf('no_private'));
        $this->assertNotNull($validator->errors()->firstOf('public_ipv4'));
        $this->assertIsString($validator->errors()->firstOf('public_ipv4'));
    }
}
