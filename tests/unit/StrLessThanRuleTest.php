<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrLessThan;

#[CoversClass(StrLessThan::class)]
class StrLessThanRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrLessThan(4);
        $this->assertTrue($rule->validate(''));
        $this->assertTrue($rule->validate('a'));
        $this->assertTrue($rule->validate('ab'));
        $this->assertTrue($rule->validate('abc'));
    }

    public function testFails(): void
    {
        $rule = new StrLessThan(4);
        $this->assertFalse($rule->validate('abcd'));
        $this->assertEquals('The field :{field} must contain less than 4 characters.', $rule->error());

        $this->assertFalse($rule->validate('abcde'));
        $this->assertEquals('The field :{field} must contain less than 4 characters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrLessThan(4);
        $this->assertFalse($rule->validate(12));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }
}
