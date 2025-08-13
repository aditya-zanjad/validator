<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\TypeJson;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(TypeJson::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class JsonValidationRuleTest extends TestCase
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
            'file_001'  =>  $this->tempDirPath . DIRECTORY_SEPARATOR . 'valid_001.json'
        ];

        file_put_contents($this->validFiles['file_001'], trim($this->makeTestJsonData()));

        $this->validFiles['file_002']   =   fopen($this->validFiles['file_001'], 'r');
        $this->validFiles['file_003']   =   file_get_contents($this->validFiles['file_001']);
    }

    /**
     * Delete all the files/directories that were created before the execution of test cases.
     *
     * @return void
     */
    public function tearDown(): void
    {
        // Clear the fetched contents of the file
        unset($this->validFiles['file_003']);

        // Clear the 'fopened' file resource
        $streamMetadata = stream_get_meta_data($this->validFiles['file_002']);
        fclose($this->validFiles['file_002']);
        unlink($streamMetadata['uri']);

        // Remove the directory
        rmdir($this->tempDirPath);
    }

    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validJson = array_merge(
            [ 'json' => '{"name": {"first": "Aditya", "last": "Zanjad"}, "age": 31, "gender": "male", "married": false}'],
            $this->validFiles
        );

        $validator = validate($validJson, [
            'json'      =>  'json',
            'file_001'  =>  'json',
            'file_002'  =>  'json',
            'file_003'  =>  'json',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('json'));
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
        $data = [
           'json_one'   =>  '{"name": {"first": "Aditya", "last": "Zanjad"}, "age": 31, "gender": "male", "married": false',
           'json_two'   =>  __DIR__ . '/invalid_directory/invalid_001.json',
           'json_three' =>  fopen(__DIR__ . '/invalid_directory/sample.txt', 'r'),
           'json_four'  =>  file_get_contents(__DIR__ . '/invalid_directory/sample.txt')
        ];

        $rules = [
            'json_one'      =>  'json',
            'json_two'      =>  'json',
            'json_three'    =>  'json',
            'json_four'     =>  'json',
        ];

        $validator = validate($data, $rules);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('json_one'));
        $this->assertNotNull($validator->errors()->firstOf('json_two'));
        $this->assertNotNull($validator->errors()->firstOf('json_three'));
        $this->assertNotNull($validator->errors()->firstOf('json_four'));
    }

    /**
     * Required for testing the assertions.
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
