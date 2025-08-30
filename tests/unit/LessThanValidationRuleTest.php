<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Lt;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(Lt::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class LessThanValidationRuleTest extends TestCase
{
    protected string $tempDirPath;

    /**
     * To contain paths to the valid files.
     *
     * @var array $files
     */
    protected array $files = [];

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

        $this->files = [
            'file_001'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'valid_001.json',
            'file_002'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'sample.txt',
            'file_003'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'sample_doc.doc',
        ];

        $fileOne = fopen($this->files['file_001'], 'w');

        // To create a file of size 2 MB.
        fseek($fileOne, (2 * 1024 * 1024) - 1);
        fwrite($fileOne, ' ');
        fclose($fileOne);

        // To create a file of size 2048 KB.
        $this->files['file_002'] = fopen($this->files['file_002'], 'w');
        fseek($this->files['file_002'], (2 * 1024 * 1024) - 1);
        fwrite($this->files['file_002'], ' ');

        // To create a file of size 2097152 bytes
        $fileThree = fopen($this->files['file_003'], 'w');
        fseek($fileThree, (2 * 1024 * 1024) - 1);
        fwrite($fileThree, ' ');
    }

    /**
     * Delete all the files/directories that were created before the execution of test cases.
     *
     * @return void
     */
    public function tearDown(): void
    {
        unlink($this->files['file_001']);
        $streamMetadata = stream_get_meta_data($this->files['file_002']);
        fclose($this->files['file_002']);
        unlink($streamMetadata['uri']);
        unlink($this->files['file_003']);
        rmdir($this->tempDirPath);
    }

    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
           'b'      =>  'b',
           123      =>  123,
           0        =>  0,
           -12345   =>  -12345,
           ...$this->files
        ], [
            'b'         =>  'lt:2',
           '123'        =>  'lt:124',
           0            =>  'lt:1',
           -12345       =>  'lt:0',
           'file_001'   =>  'lt: 3 MB',
           'file_002'   =>  'lt: 2048.1 KB',
           'file_003'   =>  'lt: 2097153'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('b'));
        $this->assertNull($validator->errors()->firstOf('123'));
        $this->assertNull($validator->errors()->firstOf('0'));
        $this->assertNull($validator->errors()->firstOf('-12345'));
        $this->assertNull($validator->errors()->firstOf('file_001'));
        $this->assertNull($validator->errors()->firstOf('file_002'));
        $this->assertNull($validator->errors()->firstOf('file_003'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
           'b'      =>  'Hello World!',
           123      =>  123,
           0        =>  0,
           -12345   =>  -12345,
           ...$this->files
        ], [
            'b'         =>  'lt:2',
           '123'        =>  'lt:120',
           0            =>  'lt:0',
           -12345       =>  'lt:-12345',
           'file_001'   =>  'lt: 1.9MB',
           'file_002'   =>  'lt: 1024 KB',
           'file_003'   =>  'lt:2097151.9',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('b'));
        $this->assertNotNull($validator->errors()->firstOf('123'));
        $this->assertNotNull($validator->errors()->firstOf('0'));
        $this->assertNotNull($validator->errors()->firstOf('-12345'));
        $this->assertNotNull($validator->errors()->firstOf('file_001'));
        $this->assertNotNull($validator->errors()->firstOf('file_002'));
        $this->assertNotNull($validator->errors()->firstOf('file_003'));

    }
}
