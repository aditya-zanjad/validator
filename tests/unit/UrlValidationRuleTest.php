<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Url::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class UrlValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc'   =>  'http://www.example.com',
            'def'   =>  'https://sub.domain.co.uk/path/file.html?query=string#fragment',
            'ghi'   =>  'ftp://ftp.example.org/dir/file',
            'jkl'   =>  'mailto:test@example.com',
            'pqr'   =>  'http://localhost',
            'uvw'   =>  'http://192.168.1.1',
            'xyz'   =>  'http://[::1]',
            'zyx'   =>  'http://example',
            'wvu'   =>  'http://foo.bar?q=Spaces should be encoded  ',
            'rqp'   =>  'http://user:pass@host.com',
            'onm'   =>  'http://example.com:8080/',
            'lkj'   =>  'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==',
            'ihg'   =>  'file:///path/to/file.txt',
        ], [
            'abc'   =>  'url',
            'def'   =>  'url',
            'ghi'   =>  'url',
            'jkl'   =>  'url',
            'mno'   =>  'url',
            'pqr'   =>  'url',
            'uvw'   =>  'url',
            'xyz'   =>  'url',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
        $this->assertEmpty($validator->errors()->firstOf('def'));
        $this->assertEmpty($validator->errors()->firstOf('ghi'));
        $this->assertEmpty($validator->errors()->firstOf('jkl'));
        $this->assertEmpty($validator->errors()->firstOf('pqr'));
        $this->assertEmpty($validator->errors()->firstOf('uvw'));
        $this->assertEmpty($validator->errors()->firstOf('xyz'));
        $this->assertEmpty($validator->errors()->firstOf('zyx'));
        $this->assertEmpty($validator->errors()->firstOf('wvu'));
        $this->assertEmpty($validator->errors()->firstOf('rqp'));
        $this->assertEmpty($validator->errors()->firstOf('onm'));
        $this->assertEmpty($validator->errors()->firstOf('lkj'));
        $this->assertEmpty($validator->errors()->firstOf('ihg'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   =>  'this is a string.',
            'def'   =>  -12311,
            'ghi'   =>  'truth',
            'jkl'   =>  new \stdClass(),
            'mno'   =>  57832572.23478235,
            'pqr'   =>  1234,
            'stu'   =>  'example.com',
            'vwx'   =>  'www.example.com',
            'wxy'   =>  'http://',
            'xyz'   =>  'http://.',
            'zyx'   =>  'http://..',
            'yxw'   =>  'http://exämple.com',
            'xwv'   =>  'a-valid-url'
        ], [
            'abc'   =>  'url',
            'def'   =>  'url',
            'ghi'   =>  'url',
            'jkl'   =>  'url',
            'mno'   =>  'url',
            'pqr'   =>  'url',
            'stu'   =>  'url',
            'vwx'   =>  'url',
            'wxy'   =>  'url',
            'xyz'   =>  'url',
            'zyx'   =>  'url',
            'yxw'   =>  'url',
            'xwv'   =>  'url'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
        $this->assertNotEmpty($validator->errors()->firstOf('ghi'));
        $this->assertNotEmpty($validator->errors()->firstOf('jkl'));
        $this->assertNotEmpty($validator->errors()->firstOf('mno'));
        $this->assertNotEmpty($validator->errors()->firstOf('pqr'));
        $this->assertNotEmpty($validator->errors()->firstOf('stu'));
        $this->assertNotEmpty($validator->errors()->firstOf('vwx'));
        $this->assertNotEmpty($validator->errors()->firstOf('wxy'));
        $this->assertNotEmpty($validator->errors()->firstOf('xyz'));
        $this->assertNotEmpty($validator->errors()->firstOf('zyx'));
        $this->assertNotEmpty($validator->errors()->firstOf('yxw'));
        $this->assertNotEmpty($validator->errors()->firstOf('xwv'));
    }
}
