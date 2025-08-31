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
final class EqualValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'digits:5' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'status'        =>  'active',
            'product_id'    =>  123,
            'is_admin'      =>  true,
            'agreement'     =>  'accepted',
        ], [
            'status'        =>  'equal:active',
            'product_id'    =>  'equal:123',
            'is_admin'      =>  'equal:true',
            'agreement'     =>  'equal:accepted',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('status'));
        $this->assertNull($validator->errors()->firstOf('product_id'));
        $this->assertNull($validator->errors()->firstOf('is_admin'));
        $this->assertNull($validator->errors()->firstOf('agreement'));
    }

    /**
     * Assert that the validation rule 'digits:5' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'status'        =>  'inactive', // Should not be 'active'
            'product_id'    =>  '123', // String '123' is not strictly equal to integer 123
            'is_admin'      =>  false, // Should be true
            'agreement'     =>  'pending', // Should be 'accepted'
        ], [
            'status'        =>  'equal:active',
            'product_id'    =>  'equal:321',
            'is_admin'      =>  'equal:true',
            'agreement'     =>  'equal:accepted',
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('status'));
        $this->assertNotNull($validator->errors()->firstOf('product_id'));
        $this->assertNotNull($validator->errors()->firstOf('is_admin'));
        $this->assertNotNull($validator->errors()->firstOf('agreement'));
    }
}
