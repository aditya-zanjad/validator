<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\ArrFilled;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ArrFilled::class)]
class ArrFilledRuleTest extends TestCase
{
    public function testArrIsFilled(): void
    {
        $rule = new ArrFilled();
        $this->assertTrue($rule->validate([1, 2, 3]));
    }

    public function testInvalidValueFailsValidation(): void
    {
        $rule = new ArrFilled();
        $this->assertFalse($rule->validate(123));
        $this->assertIsString($rule->error(), 'The field :{field} must be an array.');
    }

    public function testEmptyArrayFailsValidation(): void
    {
        $rule = new ArrFilled();
        $this->assertFalse($rule->validate([]));
        $this->assertIsString($rule->error(), 'The field :{field} must not be an empty array.');
    }
}
