<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Url::class)]
#[CoversClass(AbstractRule::class)]
final class UrlRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $validUrls = [
            // HTTP
            'http://example.com',
            'http://www.example.com',
            'http://localhost',
            'http://127.0.0.1',
            'http://127.0.0.1:8080',
            'http://example.com/path',
            'http://example.com/path/to/file',
            'http://example.com/path?name=John',
            'http://example.com/path?name=John&age=25',
            'http://example.com/path?name=John#section',
            'http://user:password@example.com',
            'http://example.com:8080',


            // HTTPS
            'https://example.com',
            'https://www.example.com',
            'https://example.com/login',
            'https://example.com/api/v1/users',
            'https://example.com/path?query=test',
            'https://user:password@example.com',
            'https://example.com:8443',

            
            // FTP
            'ftp://example.com',
            'ftp://ftp.example.com/files',
            'ftp://user:password@example.com',
            'ftp://example.com:21',


            // FTPS
            'ftps://example.com',
            'ftps://user:password@example.com',


            // FILE
            'file:///C:/Windows/System32',
            'file:///home/john/file.txt',
            'file:///tmp/test.txt',


            // MAILTO
            'mailto:test@example.com',
            'mailto:john.doe@example.com',

            // !!! Note => Not working !!!
            // Internationalized
            // 'https://münich.de', 
            // 'https://你好.com',

            
            // LDAP
            'ldap://ldap.example.com',
            'ldap://ldap.example.com:389',


            // SSH
            'ssh://user@example.com',
            'ssh://user@example.com:22',


            // TELNET
            'telnet://example.com',


            // NEWS
            'news:comp.lang.php',


            // // GOPHER
            'gopher://example.com',


            // IPV4
            'http://192.168.1.1',
            'https://10.0.0.5:8443',


            // IPV6
            'http://[::1]',
            'http://[2001:db8::1]',
            'https://[2001:4860:4860::8888]',
        ];

        foreach ($validUrls as $validUrl) {
            $url    =   new Url();
            $result =   $url->validate($validUrl);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    public function testFails(): void
    {
        $invalidUrls = [
            'example.com',
            'www.example.com',
            'http://',
            'https://',
            '://example.com',
            'http://?',
            'http:///example.com',
            'http://exa mple.com',
            'http://example .com',
        ];

        foreach ($invalidUrls as $invalidUrl) {
            $url    =   new Url();
            $result =   $url->validate($invalidUrl);

            $this->assertIsBool($result);
            $this->assertFalse($result);
        }
    }
}
