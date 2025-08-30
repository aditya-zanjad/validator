<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Lt;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\Required;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Rules\MacAddress;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Required::class)]
#[CoversClass(MacAddress::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class MacAddressValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'mac_colon'     =>  '00:0a:95:9d:68:16',
            'mac_hyphen'    =>  '00-0A-95-9D-68-16',
            'mac_dot'       =>  '000a.959d.6816',
        ], [
            'mac_colon'     =>  'mac',
            'mac_hyphen'    =>  'mac',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'mac_no_separator_lowercase' => '000a959d6816',
            'mac_no_separator_uppercase' => '000A959D6816',
            'mac_invalid_length' => '00:0a:95:9d:68:16:11',
            'mac_invalid_separator' => '00_0A_95_9D_68_16',
            'mac_invalid_char' => '00:0A:95:9D:68:1G',
            'mac_incomplete' => '00:0A:95:9D:68',
            'mac_empty' => '',
            'mac_not_a_string' => 123456,
            'mac_wrong_format' => 'ab:cd:ef:gh:ij:kl'
        ], [
            'mac_no_separator_lowercase' => 'mac',
            'mac_no_separator_uppercase' => 'mac',
            'mac_invalid_length' => 'mac',
            'mac_invalid_separator' => 'mac',
            'mac_invalid_char' => 'mac',
            'mac_incomplete' => 'mac',
            'mac_empty' => 'mac',
            'mac_not_a_string' => 'mac',
            'mac_wrong_format' => 'mac'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('mac_no_separator_lowercase'));
        $this->assertNotNull($validator->errors()->firstOf('mac_no_separator_uppercase'));
        $this->assertNotNull($validator->errors()->firstOf('mac_invalid_length'));
        $this->assertNotNull($validator->errors()->firstOf('mac_invalid_separator'));
        $this->assertNotNull($validator->errors()->firstOf('mac_invalid_char'));
        $this->assertNotNull($validator->errors()->firstOf('mac_incomplete'));
        $this->assertNotNull($validator->errors()->firstOf('mac_empty'));
        $this->assertNotNull($validator->errors()->firstOf('mac_not_a_string'));
        $this->assertNotNull($validator->errors()->firstOf('mac_wrong_format'));
    }
}
