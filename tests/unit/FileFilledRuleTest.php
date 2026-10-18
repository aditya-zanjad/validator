<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\FileFilled;

#[CoversClass(FileFilled::class)]
class FileFilledRuleTest extends TestCase
{
    protected string $filledFile;
    protected string $emptyFile;

    protected function setUp(): void
    {
        $this->filledFile = tempnam(sys_get_temp_dir(), 'filled_');
        file_put_contents($this->filledFile, 'not empty');

        $this->emptyFile = tempnam(sys_get_temp_dir(), 'empty_');
        file_put_contents($this->emptyFile, '');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filledFile)) {
            @unlink($this->filledFile);
        }
        if (file_exists($this->emptyFile)) {
            @unlink($this->emptyFile);
        }
    }

    public function testPassesForNonEmptyFile(): void
    {
        $rule = new FileFilled();
        $this->assertTrue($rule->validate($this->filledFile));
    }

    public function testFailsForEmptyFile(): void
    {
        $rule = new FileFilled();
        $this->assertFalse($rule->validate($this->emptyFile));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }

    public function testFailsForInvalidFile(): void
    {
        $rule = new FileFilled();
        $this->assertFalse($rule->validate('non_existent.txt'));
        $this->assertEquals('The field :{field} must not be an empty file.', $rule->error());
    }
}
