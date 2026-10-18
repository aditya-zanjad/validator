<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Email;

#[CoversClass(Email::class)]
class EmailRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new Email();
        $this->assertTrue($rule->validate('test@example.com'));
        $this->assertTrue($rule->validate('user.name+tag@sub.domain.org'));
    }

    public function testFails(): void
    {
        $rule = new Email();
        $this->assertFalse($rule->validate('plainaddress'));
        $this->assertEquals('The field :{field} must be a valid email.', $rule->error());

        $this->assertFalse($rule->validate('@missingusername.com'));
        $this->assertFalse($rule->validate('username@.com'));
        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
    }
}
