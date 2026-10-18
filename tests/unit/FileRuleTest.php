<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\File;

#[CoversClass(File::class)]
class FileRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_test_');
        file_put_contents($this->tempFile, 'test file content');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesForFilePath(): void
    {
        $rule = new File();
        $this->assertTrue($rule->validate($this->tempFile));
    }

    public function testPassesForSplFileInfo(): void
    {
        $rule = new File();
        $this->assertTrue($rule->validate(new SplFileInfo($this->tempFile)));
    }

    public function testPassesForResource(): void
    {
        $rule = new File();
        $resource = fopen($this->tempFile, 'r');
        try {
            $this->assertTrue($rule->validate($resource));
        } finally {
            fclose($resource);
        }
    }

    public function testFailsForNonExistentFile(): void
    {
        $rule = new File();
        $this->assertFalse($rule->validate($this->tempFile . '_non_existent'));
        $this->assertEquals('The field :{field} must be a valid file.', $rule->error());
    }

    public function testFailsForInvalidTypes(): void
    {
        $rule = new File();
        $this->assertFalse($rule->validate(12345));
        $this->assertFalse($rule->validate(null));
        $this->assertFalse($rule->validate([]));
    }
}
