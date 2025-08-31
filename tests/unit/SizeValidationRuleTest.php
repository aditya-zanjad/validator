<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Size;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Size::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class SizeValidationRuleTest extends TestCase
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
            'abc'       =>  123456,
            'def'       =>  0,
            'ghi'       =>  'abc',
            'jkl'       =>  'x',
            'xyz'       =>  -20,
            
            ...$this->files
        ], [
            'abc'       =>  'size:123456',
            'def'       =>  'size:0',
            'ghi'       =>  'size:3',
            'jkl'       =>  'size:1',
            'xyz'       =>  'size:-20',
            'file_001'  =>  'size: 2 MB ',
            'file_002'  =>  'size: 2048 KB',
            'file_003'  =>  'size:2097152'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
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
            'abc'       =>  123456,
            'def'       =>  0,
            'ghi'       =>  'abc',
            'jkl'       =>  'x',
            'xyz'       =>  -20,

            ...$this->files
        ], [
            'abc'       =>  'size:10',
            'def'       =>  'size:-1',
            'ghi'       =>  'size:2',
            'jkl'       =>  'size:0',
            'xyz'       =>  'size:-100',
            'file_001'  =>  'size: 1GB',
            'file_002'  =>  'size:  4096     KB     ',
            'file_003'  =>  'size: 1231912848'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertIsString($validator->errors()->first());
        $this->assertIsString($validator->errors()->firstOf('abc'));
        $this->assertIsString($validator->errors()->firstOf('def'));
        $this->assertIsString($validator->errors()->firstOf('ghi'));
        $this->assertIsString($validator->errors()->firstOf('jkl'));
        $this->assertIsString($validator->errors()->firstOf('file_001'));
        $this->assertIsString($validator->errors()->firstOf('file_002'));
        $this->assertIsString($validator->errors()->firstOf('file_003'));
    }
}
