<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\TypeFile;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TypeFile::class)]
final class FileValidationRuleTest extends TestCase
{
    /**
     * Directory path to a temporary directory.
     *
     * @var string $tempDirPath
     */
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
            'file_path' => $this->tempDirPath . DIRECTORY_SEPARATOR . 'valid_001.json',
        ];

        file_put_contents($this->validFiles['file_path'], trim($this->makeTestJsonData()));

        $this->validFiles['file_resource'] = fopen($this->validFiles['file_path'], 'r');

        if (\extension_loaded('SPL') && \class_exists(SplFileInfo::class)) {
            $this->validFiles['file_object'] = new SplFileInfo($this->validFiles['file_path']);
        }
    }

    /**
     * Delete all the files/directories that were created before the execution of test cases.
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->validFiles['file_object']);
        fclose($this->validFiles['file_resource']);
        unlink($this->validFiles['file_path']);
        unset($this->validFiles['file_path']);
        rmdir($this->tempDirPath);
    }

    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testPasses(): void
    {
        foreach ($this->validFiles as $file) {
            $rule   =   new TypeFile();
            $result =   $rule->check($file);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testFails(): void
    {
        $data = [
            '/invalid_directory/invalid_file.json',
            'This is a test string',
            new ArrayObject(),
            new stdClass(),
            true,
            false,
            'true',
            'false',
            123123,
            12312.123123
            -123123,
            -683454393,
            ['this is a test string inside a test array!'],
            '1234! Get on the dance floor!'
        ];

        foreach ($data as $file) {
            $rule   =   new TypeFile();
            $result =   $rule->check($file);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be a file.');
        }
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
}
