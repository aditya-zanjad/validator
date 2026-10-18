<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileBetween;

#[CoversClass(FileBetween::class)]
class FileBetweenRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_test_');
        file_put_contents($this->tempFile, str_repeat('A', 50)); // 50 bytes
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesWhenSizeIsBetween(): void
    {
        $rule = new FileBetween(10, 100);
        $this->assertTrue($rule->validate($this->tempFile));
    }

    public function testFailsWhenSizeIsOutside(): void
    {
        $rule = new FileBetween(100, 200);
        $this->assertFalse($rule->validate($this->tempFile));
        $this->assertEquals('The file :{field} size must be in the range [100 - 200].', $rule->error());
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileBetween(10, 100);
        $this->assertFalse($rule->validate('non_existent_file.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testInvalidConstructorParametersThrowException(): void
    {
        $this->expectException(\Exception::class);
        new FileBetween('invalid-size', 100);
    }

    public function testMaxLessThanMinThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new FileBetween(200, 100);
    }
}
