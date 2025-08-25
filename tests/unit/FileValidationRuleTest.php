<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\TypeFile;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeFile::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class FileValidationRuleTest extends TestCase
{
    protected string $tempDirPath;

    /**
     * To contain paths to the valid files.
     *
     * @var array $validFiles
     */
    protected array $validFiles = [];

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->tempDirPath = __DIR__ . DIRECTORY_SEPARATOR . 'temp';

        if (!is_dir($this->tempDirPath)) {
            mkdir($this->tempDirPath, 0775, true);
        }

        chmod($this->tempDirPath, 0775);

        $this->validFiles = [
            'file_001'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'valid_001.json',
            'file_002'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'sample.txt',
        ];

        file_put_contents($this->validFiles['file_001'], trim($this->makeTestJsonData()));
        file_put_contents($this->validFiles['file_002'], trim($this->makeTestTextData()));

        $this->validFiles['file_002'] = fopen($this->validFiles['file_002'], 'r');
    }

    /**
     * Delete all the files/directories that were created before the execution of test cases.
     *
     * @return void
     */
    public function tearDown(): void
    {
        unlink($this->validFiles['file_001']);
        $streamMetadata = stream_get_meta_data($this->validFiles['file_002']);
        fclose($this->validFiles['file_002']);
        unlink($streamMetadata['uri']);
        rmdir($this->tempDirPath);
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate($this->validFiles, [
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
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'file_002'  =>  '/invalid_directory/invalid_file.json',
            'file_003'  =>  'This is a test string',
            'file_004'  =>  @fopen('/path/to/invalid/file.txt', 'r'),
            'file_005'  =>  @file_get_contents('/path/to/invalid/file.txt')
        ], [
            'file_002'  =>  'file',
            'file_003'  =>  'file',
            'file_004'  =>  'file',
            'file_005'  =>  'file',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('file_002'));
        $this->assertNotEmpty($validator->errors()->firstOf('file_003'));
        $this->assertNotEmpty($validator->errors()->firstOf('file_004'));
        $this->assertNotEmpty($validator->errors()->firstOf('file_005'));
    }

    /**
     * Required for performing the test assertions.
     *
     * @return string
     */
    protected function makeTestJsonData(): string
    {
        return '
            [{
                "id": 1,
                "first_name": "Jeanette",
                "last_name": "Penddreth",
                "email": "jpenddreth0@census.gov",
                "gender": "Female",
                "ip_address": "26.58.193.2"
                }, {
                "id": 2,
                "first_name": "Giavani",
                "last_name": "Frediani",
                "email": "gfrediani1@senate.gov",
                "gender": "Male",
                "ip_address": "229.179.4.212"
                }, {
                "id": 3,
                "first_name": "Noell",
                "last_name": "Bea",
                "email": "nbea2@imageshack.us",
                "gender": "Female",
                "ip_address": "180.66.162.255"
                }, {
                "id": 4,
                "first_name": "Willard",
                "last_name": "Valek",
                "email": "wvalek3@vk.com",
                "gender": "Male",
                "ip_address": "67.76.188.26"
            }]
        ';
    }

    /**
     * Required for performing the test assertions.
     *
     * @return string
     */
    protected function makeTestTextData(): string
    {
        return 'Hello World! 1234! Get on the dance floor!';
    }
}
