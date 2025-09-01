<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Rules\TypeString;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TypeString::class)]
#[UsesClass(StringableImplementor::class)]
final class StringValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'StringableImplementor.php';

        $data = [
            'first'     =>  '',
            'second'    =>  '1234! Get on the dance floor!',
            'third'     =>  new StringableImplementor('abcdefghijklmnopqrstuvxyz'),
            'fourth'    =>  '1234.567',
            'fifth'     =>  '1234',
            'sixth'     =>  'true',
        ];

        foreach ($data as $key => $value) {
            $rule   =   new TypeString();
            $result =   $rule->check($key, $value);

            $this->assertIsBool($result);
            $this->assertEquals($result, true);
        }
    }

    /**
     * @return void
     */
    public function testRegexesPass(): void
    {
        $data = [
            'first' => [
                'regex' =>  '/^[0-9]+$/',
                'value' =>  '1234571239'
            ],
            'second' => [
                'regex' =>  '/^[a-zA-Z_-]+$/',
                'value' =>  'abcd_efghi-ABCDEf'
            ],
            'third' => [
                'regex' => '/^[a-z0-9_]+$/',
                'value' => 'abcde_909234'
            ]
        ];

        foreach ($data as $field => $options) {
            $rule   = new TypeString($options['regex']);
            $result = $rule->check($field, $options['value']);

            $this->assertTrue($result);
            $this->assertEquals($result, true);
        }
    }

    /**
     * @return void
     */
    public function testFails(): void
    {
        $tmpFile = tmpfile();
        fwrite($tmpFile, 'This is a temp text inside a temp file!');

        $data = [
            'first'     =>  12345,
            'second'    =>  4575.123421,
            'third'     =>  ['This is a test string'],
            'fourth'    =>  new \stdClass(),
            'fifth'     =>  true,
            'sixth'     =>  $tmpFile,
            'seventh'   =>  null
        ];

        foreach ($data as $key => $value) {
            $rule   =   new TypeString();
            $result =   $rule->check($key, $value);

            $this->assertIsBool($result);
            $this->assertEquals($result, false);
            $this->assertEquals($rule->message(), 'The field :{field} must be a string.');
        }

        fclose($tmpFile);
    }

    /**
     * @return void
     */
    public function testRegexesFail(): void
    {
        $data = [
            'first' => [
                'regex' =>  '/^[0-9]$/',
                'value' =>  '1234571239'
            ],
            'second' => [
                'regex' =>  '/^[a-zA-Z_-]+$/',
                'value' =>  '1234! Get on the dance floor!'
            ],
            'third' => [
                'regex' => '/^[a-z0-9_]+$/',
                'value' => '-----~@$%@%'
            ]
        ];

        $messages = [
            "The field :{field} must match the regular expression: /^[0-9]$/.",
            "The field :{field} must match the regular expression: /^[a-zA-Z_-]+$/.",
            "The field :{field} must match the regular expression: /^[a-z0-9_]+$/.",
        ];

        foreach ($data as $field => $options) {
            $rule   =   new TypeString($options['regex']);
            $result =   $rule->check($field, $options['value']);

            $this->assertTrue($result);
            $this->assertEquals($result, true);
            $this->assertEquals(\in_array($rule->message(), $messages, true), true);
        }
    }
}
