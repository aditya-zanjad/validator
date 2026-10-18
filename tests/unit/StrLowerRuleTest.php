<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrLower;

#[CoversClass(StrLower::class)]
class StrLowerRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrLower();
        $this->assertTrue($rule->validate('lowercase'));
        $this->assertTrue($rule->validate('lowercase 123!'));
        $this->assertTrue($rule->validate(''));
    }

    public function testFails(): void
    {
        $rule = new StrLower();
        $this->assertFalse($rule->validate('Uppercase'));
        $this->assertEquals('The field :{field} must contain only the lowercase letters.', $rule->error());

        $this->assertFalse($rule->validate('LOWER'));
        $this->assertEquals('The field :{field} must contain only the lowercase letters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrLower();
        $this->assertFalse($rule->validate(['test']));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }
}
