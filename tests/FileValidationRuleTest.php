<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\TypeFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Utils\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeFile::class)]
#[CoversFunction('\AdityaZanjad\Validator\Utils\validate')]
final class FileValidationRuleTest extends TestCase
{
/**
 * Assert that the validator fails when the given string is an invalid string.
 *
 * @return void
 */
public function testFileValidationRulePasses(): void
{
    $validator = validate([
        'file_001'  =>  __DIR__ . '/test-files/valid_001.json',
        'file_002'  =>  fopen(__DIR__ . '/test-files/sample.txt', 'r'),
    ], [
        'file_001' => 'file',
        'file_002' => 'file',
    ]);

    $this->assertFalse($validator->failed());
    $this->assertEmpty($validator->errors()->all());
    $this->assertEmpty($validator->errors()->firstOf('file_001'));
    $this->assertEmpty($validator->errors()->firstOf('file_002'));
}

/**
 * Assert that the validator succeeds when the given fields are valid.
 *
 * @return void
 */
public function testFileValidationRuleFails(): void
{
    $validator = validate([
        'file_002'  =>  '/json/valid_001.json',
        'file_003'  =>  'This is a test string'
    ], [
        'file_001'  =>  'file',
        'file_002'  =>  'file',
        'file_003'  =>  'file',
    ]);

    $this->assertTrue($validator->failed());
    $this->assertNotEmpty($validator->errors()->all());
    $this->assertNotEmpty($validator->errors()->firstOf('file_002'));
    $this->assertNotEmpty($validator->errors()->firstOf('file_003'));
}
}
