<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Json;

#[CoversClass(Json::class)]
class JsonRuleTest extends TestCase
{
    public function testPassesForValidJsonString(): void
    {
        $rule = new Json();
        $this->assertTrue($rule->validate('{"key": "value"}'));
        $this->assertTrue($rule->validate('[1, 2, 3]'));
        $this->assertTrue($rule->validate('"just a string"'));
        $this->assertTrue($rule->validate('true'));
        $this->assertTrue($rule->validate('123'));
    }

    public function testFailsForInvalidJson(): void
    {
        $rule = new Json();
        $this->assertFalse($rule->validate('{invalid: json}'));
        $this->assertEquals('The field :{field} must be a valid JSON.', $rule->error());

        $this->assertFalse($rule->validate(''));
        $this->assertFalse($rule->validate(['array']));
        $this->assertFalse($rule->validate(null));
    }

    public function testPassesForJsonFile(): void
    {
        $rule = new Json();
        $tempFile = tempnam(sys_get_temp_dir(), 'test_json_');
        file_put_contents($tempFile, '{"valid": true}');

        try {
            $this->assertTrue($rule->validate($tempFile));
        } finally {
            @unlink($tempFile);
        }
    }
}
