<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\StrUpper;

#[CoversClass(StrUpper::class)]
class StrUpperRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $rule = new StrUpper();
        $this->assertTrue($rule->validate('UPPERCASE'));
        $this->assertTrue($rule->validate('UPPERCASE 123!'));
        $this->assertTrue($rule->validate(''));
    }

    public function testFails(): void
    {
        $rule = new StrUpper();
        $this->assertFalse($rule->validate('lowercase'));
        $this->assertEquals('The field :{field} must contain only the uppercase letters.', $rule->error());

        $this->assertFalse($rule->validate('MixedCase'));
        $this->assertEquals('The field :{field} must contain only the uppercase letters.', $rule->error());
    }

    public function testInvalidTypeFailsValidation(): void
    {
        $rule = new StrUpper();
        $this->assertFalse($rule->validate(12345));
        $this->assertEquals('The field :{field} must be a string.', $rule->error());
    }
}
