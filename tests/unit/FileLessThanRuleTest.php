<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileLessThan;

#[CoversClass(FileLessThan::class)]
class FileLessThanRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_lt_');
        file_put_contents($this->tempFile, str_repeat('Y', 50)); // 50 bytes
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesWhenFileSizeIsLessThanLimit(): void
    {
        $rule = new FileLessThan(100);
        $this->assertTrue($rule->validate($this->tempFile));
    }

    public function testFailsWhenFileSizeIsNotLessThanLimit(): void
    {
        $rule = new FileLessThan(50);
        $this->assertFalse($rule->validate($this->tempFile));
        $this->assertEquals('The file :{field} must be less than the size 50.', $rule->error());

        $rule2 = new FileLessThan(20);
        $this->assertFalse($rule2->validate($this->tempFile));
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileLessThan(100);
        $this->assertFalse($rule->validate('missing_file.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testInvalidConstructorParameterThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new FileLessThan('invalid-size');
    }
}
