<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\In;
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
#[CoversClass(In::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
class InValidationRuleTest extends TestCase
{
    /**
     * Assert that the validation rule 'min:' succeeds.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'ghi'   =>  'abc',
            'jkl'   =>  true,
            'mno'   =>  1234,
            'pqr'   =>  '456',
        ], [
            'ghi'   =>  'required|in:abc,def',
            'jkl'   =>  'required|boolean|in:true,false',
            'mno'   =>  'required|in: 1234, 5678,   90',
            'pqr'   =>  'required|in:123, abc,  456'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->first());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
        $this->assertNull($validator->errors()->firstOf('mno'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
    }

    /**
     * Assert that the validation rule 'min:' fails.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc'   =>  '   ',
            'def'   =>  '        123141        ',
            'ghi'   =>  'abc',
            'jkl'   =>  'TRUE',
            'mno'   =>  1234,
            'pqr'   =>  '456',
        ], [
            'abc'   =>  'required|in: 123, 456',
            'def'   =>  'required|in:Hello\, World!',
            'ghi'   =>  'required|in:cba,def',
            'jkl'   =>  'required|boolean|in:true,false',
            'mno'   =>  'required|in: 12345, 67890,',
            'pqr'   =>  'required|in:123, abc,  654'
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotNull($validator->errors()->first());
        $this->assertNotNull($validator->errors()->firstOf('abc'));
        $this->assertNotNull($validator->errors()->firstOf('def'));
        $this->assertNotNull($validator->errors()->firstOf('ghi'));
        $this->assertNotNull($validator->errors()->firstOf('jkl'));
        $this->assertNotNull($validator->errors()->firstOf('mno'));
        $this->assertNotNull($validator->errors()->firstOf('pqr'));
    }
}
