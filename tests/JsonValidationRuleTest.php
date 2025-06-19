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

use function AdityaZanjad\Validator\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(TypeJson::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
class JsonValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testJsonValidationRulePasses(): void
    {
        $validator = validate([
           'json_one'   =>  '{"name": {"first": "Aditya", "last": "Zanjad"}, "age": 31, "gender": "male", "married": false}',
           'json_two'   =>  __DIR__ . '/test-files/valid_001.json',
           'json_three' =>  fopen(__DIR__ . '/test-files/valid_002.json', 'r'),
           'json_four'  =>  file_get_contents(__DIR__ . '/test-files/valid_001.json')
        ], [
            'json_one'      =>  'required|string|json',
            'json_two'      =>  'required|file|json',
            'json_three'    =>  'json',
            'json_four'     =>  'json',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('json_one'));
        $this->assertNull($validator->errors()->firstOf('json_two'));
        $this->assertNull($validator->errors()->firstOf('json_three'));
        $this->assertNull($validator->errors()->firstOf('json_four'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testJsonValidationRuleFails(): void
    {
        $data = [
           'json_one'   =>  '{"name": {"first": "Aditya", "last": "Zanjad"}, "age": 31, "gender": "male", "married": false',
           'json_two'   =>  __DIR__ . '/test-files/invalid_001.json',
           'json_three' =>  fopen(__DIR__ . '/test-files/sample.txt', 'r'),
           'json_four'  =>  file_get_contents(__DIR__ . '/test-files/sample.txt')
        ];

        $rules = [
            'json_one'      =>  'required|string|json',
            'json_two'      =>  'required|file|json',
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
}
