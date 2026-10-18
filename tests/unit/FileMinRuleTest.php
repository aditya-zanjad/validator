<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileMin;

#[CoversClass(FileMin::class)]
class FileMinRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_min_');
        file_put_contents($this->tempFile, str_repeat('W', 50)); // 50 bytes
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesWhenFileSizeIsAtLeastLimit(): void
    {
        $rule = new FileMin(50); // boundary allowed
        $this->assertTrue($rule->validate($this->tempFile));

        $rule2 = new FileMin(20);
        $this->assertTrue($rule2->validate($this->tempFile));
    }

    public function testFailsWhenFileSizeIsBelowLimit(): void
    {
        $rule = new FileMin(60);
        $this->assertFalse($rule->validate($this->tempFile));
        $this->assertEquals('The file :{field} must be of at least the size 60.', $rule->error());
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileMin(20);
        $this->assertFalse($rule->validate('missing_file.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testInvalidConstructorParameterThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new FileMin('invalid-size');
    }
}
