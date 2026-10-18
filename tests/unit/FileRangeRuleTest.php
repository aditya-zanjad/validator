<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileRange;

#[CoversClass(FileRange::class)]
class FileRangeRuleTest extends TestCase
{
    protected string $tempFile;

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'val_range_');
        file_put_contents($this->tempFile, str_repeat('R', 50)); // 50 bytes
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    public function testPassesWhenFileSizeIsWithinRange(): void
    {
        $rule = new FileRange(50, 100); // 50 allowed
        $this->assertTrue($rule->validate($this->tempFile));

        $rule2 = new FileRange(20, 50); // 50 allowed
        $this->assertTrue($rule2->validate($this->tempFile));

        $rule3 = new FileRange(20, 100);
        $this->assertTrue($rule3->validate($this->tempFile));
    }

    public function testFailsWhenFileSizeIsOutOfRange(): void
    {
        $rule = new FileRange(60, 100);
        $this->assertFalse($rule->validate($this->tempFile));
        $this->assertEquals('The file :{field} size must be in the range [60 - 100].', $rule->error());

        $rule2 = new FileRange(10, 40);
        $this->assertFalse($rule2->validate($this->tempFile));
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileRange(10, 100);
        $this->assertFalse($rule->validate('missing_file.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testInvalidConstructorParametersThrowException(): void
    {
        $this->expectException(\Exception::class);
        new FileRange('invalid', 100);
    }

    public function testMaxLessThanMinThrowsException(): void
    {
        $this->expectException(\Exception::class);
        new FileRange(100, 50);
    }
}
