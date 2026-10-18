<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Ulid;

#[CoversClass(Ulid::class)]
class UlidRuleTest extends TestCase
{
    public function testPassesForValidUlid(): void
    {
        $rule = new Ulid();
        // Valid 26-char Crockford base32 ULID
        $this->assertTrue($rule->validate('01ARZ3NDEKTSV4RRFFQ69G5FAV'));
        $this->assertTrue($rule->validate('01arz3ndektsv4rrffq69g5fav'));
    }

    public function testFailsForInvalidUlid(): void
    {
        $rule = new Ulid();
        $this->assertFalse($rule->validate('not-a-ulid'));
        $this->assertEquals('The field :{field} must be a valid ULID.', $rule->error());

        // 25 characters (too short)
        $this->assertFalse($rule->validate('01ARZ3NDEKTSV4RRFFQ69G5FA'));

        // Invalid characters (I, L, O, U are not in Crockford base32)
        $this->assertFalse($rule->validate('01ARZ3NDEKTSV4RRFFQ69G5FAI'));

        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
    }
}
