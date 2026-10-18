<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\Url;

#[CoversClass(Url::class)]
class UrlRuleTest extends TestCase
{
    public function testPassesForValidUrls(): void
    {
        $rule = new Url();
        $this->assertTrue($rule->validate('https://www.google.com'));
        $this->assertTrue($rule->validate('http://example.com/path?foo=bar#hash'));
        $this->assertTrue($rule->validate('ftp://ftp.example.com'));
    }

    public function testFailsForInvalidUrls(): void
    {
        $rule = new Url();
        $this->assertFalse($rule->validate('not a url'));
        $this->assertEquals('The field :{field} must be a valid URL.', $rule->error());

        $this->assertFalse($rule->validate('://missing-scheme.com'));
        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
    }
}
