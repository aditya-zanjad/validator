<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
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
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class RequiredValidationRuleTest extends TestCase
{
    /**
     * Path to the file.
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

        if (!\is_dir($this->tempDirPath)) {
            \mkdir($this->tempDirPath, 0775, true);
        }

        \chmod($this->tempDirPath, 0775);

        $this->validFiles = [
            'file_001'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'sample.txt',
        ];

        \file_put_contents($this->validFiles['file_001'], trim($this->makeTestTextData()));
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        \unlink($this->validFiles['file_001']);
        \rmdir($this->tempDirPath);
    }

    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'       =>  [null],
            'xyz'       =>  'abc',
            'pqr'       =>  false,
            123         =>  0,
            456         =>  '0',
            789         =>  'false',
            ...$this->validFiles
        ], [
            'abc'       =>  'required',
            'xyz'       =>  'required',
            'pqr'       =>  'required',
            123         =>  'required',
            456         =>  'required',
            789         =>  'required',
            'file_001'  =>  'required'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('xyz'));
        $this->assertNull($validator->errors()->firstOf('123'));
        $this->assertNull($validator->errors()->firstOf('456'));
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('abc'));
    }

    /**
     * Assert that the validation fails when the required field is missing.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'def'   =>  null,
            'ghi'   =>  '',
            'jkl'   =>  []
        ], [
            'abc'   =>  'required',
            'def'   =>  'required',
            'jkl'   =>  'required',

        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->all());
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
