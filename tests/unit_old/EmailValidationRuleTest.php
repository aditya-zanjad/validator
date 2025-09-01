<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Email::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class EmailValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'standard_email'    =>  'test@example.com',
            'sub_domain'        =>  'first.last@sub.domain.co.uk',
            'plus_addressing'   =>  'user+tagging@example.com',
            'hyphens_numbers'   =>  'user-123@domain-name.com',
        ], [
            'standard_email'    =>  'email',
            'subdomain'         =>  'email',
            'plus_addressing'   =>  'email',
            'hyphens_numbers'   =>  'email'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('standard_email'));
        $this->assertNull($validator->errors()->firstOf('subdomain'));
        $this->assertNull($validator->errors()->firstOf('plus_addressing'));
        $this->assertNull($validator->errors()->firstOf('hyphens_numbers'));
    }

    /**
     * Assert that the validation rule 'digits:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'missing_at'        =>  'test-example.com',
            'missing_domain'    =>  'test@',
            'invalid_chars'     =>  'test@example_com',
            'multiple_at'       =>  'test@example@com',
        ], [
            'missing_at'        =>  'email',
            'missing_domain'    =>  'email',
            'invalid_chars'     =>  'email',
            'multiple_at'       =>  'email',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('missing_at'));
        $this->assertNotNull($validator->errors()->firstOf('missing_domain'));
        $this->assertNotNull($validator->errors()->firstOf('invalid_chars'));
        $this->assertNotNull($validator->errors()->firstOf('multiple_at'));
    }
}
