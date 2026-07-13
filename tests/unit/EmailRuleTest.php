<?php

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Email;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Email::class)]
final class EmailRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $validEmails = [
            'test@example.com',
            'john.doe@example.com',
            'john@example.co.uk',
            'user123@example.com',
            'user_name@example.com',
            'user-name@example.com',
            'user+tag@example.com',
            'user+newsletter@example.co.in',
            'firstname.lastname@example.com',
            '123@example.com',
            'a@example.com',

            'john@mail.example.com',
            'john@dev.mail.example.com',
            'user@sub.domain.example.org',

            'user1@example.com',
            '123456@example.com',
            'abc123@example.com',

            'user!@example.com',
            'user#123@example.com',
            'user$money@example.com',
            'user%test@example.com',
            'user&admin@example.com',
            "user'*test@example.com",
            'user+tag@example.com',
            'user-test@example.com',
            'user/test@example.com',
            'user=test@example.com',
            'user?test@example.com',
            'user^test@example.com',
            'user_test@example.com',
            'user`test@example.com',
            'user{test}@example.com',
            'user|test@example.com',
            'user~test@example.com',
            
            '用户@example.com',
            'δοκιμή@example.com',
            'अजय@example.com',

            '"john..doe"@example.com',
            '"john@doe"@example.com',

            'user@example.com',
            'user@example.co.uk',
            'user@example.io',
            'user@example.travel',
            'user@example.museum',
            'user@example.photography',
        ];

        foreach ($validEmails as $validEmail) {
            $email = new Email();
            $result = $email->validate($validEmail);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    public function testFails()
    {
        $invalidEmails = [
            // Empty
            '',
            ' ',
            "\t",
            "\n",

            // Missing local part
            '@example.com',

            // Missing domain
            'john@',

            // Missing @
            'example.com',
            'john.example.com',

            // Multiple @
            'john@@example.com',
            'john@example@com',
            '@@',

            // Consecutive dots
            'john..doe@example.com',
            'john@example..com',

            // Leading/trailing dots
            '.john@example.com',
            'john.@example.com',
            'john@.example.com',
            'john@example.com.',

            // Spaces
            'john doe@example.com',
            ' john@example.com',
            'john@example.com ',
            'john@exam ple.com',

            // Invalid characters
            'john<doe@example.com',
            'john>doe@example.com',
            'john(doe@example.com',
            'john)doe@example.com',
            'john[doe@example.com',
            'john]doe@example.com',
            'john:doe@example.com',
            'john;doe@example.com',
            'john,doe@example.com',
            'john\\doe@example.com',

            // Domain issues
            'john@example',
            'john@localhost',
            'john@com',
            'john@.com',
            'john@..com',
            'john@com.',
            'john@.example.com',

            // Hyphens
            'john@-example.com',
            'john@example-.com',
            'john@sub.-example.com',
            'john@sub.example-.com',

            // Invalid TLD
            'john@example.123',
            'john@example.-com',

            // Empty labels
            'john@example..org',

            // Invalid IP literals
            'john@[300.300.300.300]',
            'john@[127.0.0]',
            'john@[::gggg]',

            // Missing hostname
            'john@[]',

            // Invalid quoted strings
            '"john@example.com',
            'john"@example.com',
            '"john@example.com"',
            '"john"example@example.com',

            // Extra punctuation
            'john.@.example.com',
            '..john@example.com',

            // Control characters
            "john\r@example.com",
            "john\n@example.com",
            "john\t@example.com",

            // Multiple dots in domain
            'john@example...com',

            // Invalid IDN
            'john@münich',

            // Invalid schemes
            'mailto:test@example.com',
            'https://example.com',

            // Random strings
            'abc',
            '123',
            '!!!!',
            'john@#$.com',
        ];

        foreach ($invalidEmails as $invalidEmail) {
            $email = new Email();
            $result = $email->validate($invalidEmail);

            if ($result) {
                $this->assertIsBool($result);
            }

            $this->assertIsBool($result);
            $this->assertFalse($result);
        }
    }
}
