<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrBetween;

#[CoversClass(StrBetween::class)]
class StrBetweenRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrBetween(2, 5);
        $this->assertTrue($rule->validate('abc')); // 3 characters
        $this->assertTrue($rule->validate('abcd')); // 4 characters
    }

    public function testFails(): void
    {
        $rule = new StrBetween(2, 5);
        $this->assertFalse($rule->validate('ab')); // 2 characters (exclusive)
        $this->assertEquals('The field :{field} must contain characters between the [2 - 5] characters.', $rule->error());

        $this->assertFalse($rule->validate('abcde')); // 5 characters (exclusive)
        $this->assertEquals('The field :{field} must contain characters between the [2 - 5] characters.', $rule->error());

        $this->assertFalse($rule->validate('a')); // 1 character
        $this->assertFalse($rule->validate('abcdef')); // 6 characters
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrBetween(2, 5);
        $this->assertFalse($rule->validate(['abc']));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }

    public function testNegativeLengthsCauseException(): void
    {
        $this->expectException(\Exception::class);
        new StrBetween(-1, 5);
    }

    public function testMinGreaterThanOrEqualToMaxCausesException(): void
    {
        $this->expectException(\Exception::class);
        new StrBetween(5, 2);
    }
}
