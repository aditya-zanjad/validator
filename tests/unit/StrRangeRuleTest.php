<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrRange;

#[CoversClass(StrRange::class)]
class StrRangeRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrRange(2, 4);
        $this->assertTrue($rule->validate('ab'));
        $this->assertTrue($rule->validate('abc'));
        $this->assertTrue($rule->validate('abcd'));
    }

    public function testFails(): void
    {
        $rule = new StrRange(2, 4);
        $this->assertFalse($rule->validate('a'));
        $this->assertEquals('The field :{field} must contain characters in the allowed range [2 - 4].', $rule->error());

        $this->assertFalse($rule->validate('abcde'));
        $this->assertEquals('The field :{field} must contain characters in the allowed range [2 - 4].', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrRange(2, 4);
        $this->assertFalse($rule->validate(123));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }

    public function testNegativeLimitsCauseException(): void
    {
        $this->expectException(\Exception::class);
        new StrRange(-1, 5);
    }

    public function testMinGreaterThanOrEqualToMaxCausesException(): void
    {
        $this->expectException(\Exception::class);
        new StrRange(5, 2);
    }
}
