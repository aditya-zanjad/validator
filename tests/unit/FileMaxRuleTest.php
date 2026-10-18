<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileMax;

#[CoversClass(FileMax::class)]
class FileMaxRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_max_');
        file_put_contents($this->tempFile, str_repeat('Z', 50)); // 50 bytes
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesWhenFileSizeIsAtMostLimit(): void
    {
        $rule = new FileMax(50); // boundary allowed
        $this->assertTrue($rule->validate($this->tempFile));

        $rule2 = new FileMax(100);
        $this->assertTrue($rule2->validate($this->tempFile));
    }

    public function testFailsWhenFileSizeExceedsLimit(): void
    {
        $rule = new FileMax(40);
        $this->assertFalse($rule->validate($this->tempFile));
        $this->assertEquals('The file :{field} must not be greater than 40.', $rule->error());
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileMax(50);
        $this->assertFalse($rule->validate('missing_file.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testInvalidConstructorParameterThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new FileMax('invalid-size');
    }
}
