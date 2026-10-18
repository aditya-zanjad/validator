<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Uuid;

#[CoversClass(Uuid::class)]
class UuidRuleTest extends TestCase
{
    public function testPassesForValidUuidV4(): void
    {
        $rule = new Uuid('v4');
        $this->assertTrue($rule->validate('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11'));
        $this->assertTrue($rule->validate('A0EEBC99-9C0B-4EF8-BB6D-6BB9BD380A11'));
    }

    public function testPassesForValidUuidV1(): void
    {
        $rule = new Uuid('v1');
        $this->assertTrue($rule->validate('216fff40-98d9-11e3-a5e2-0800200c9a66'));
    }

    public function testFailsForInvalidUuid(): void
    {
        $rule = new Uuid('v4');
        $this->assertFalse($rule->validate('not-a-uuid'));
        $this->assertEquals('The field :{field} must be a valid UUID v4 string.', $rule->error());

        // V1 uuid passed to V4 validator
        $this->assertFalse($rule->validate('216fff40-98d9-11e3-a5e2-0800200c9a66'));
        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
    }

    public function testUnsupportedUuidVersionThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The validation rule [uuid] is supplied with an invalid/unsupported UUID version: [v99]');
        $rule = new Uuid('v99');
        $rule->validate('a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11');
    }
}
