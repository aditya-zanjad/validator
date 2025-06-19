<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use AdityaZanjad\Validator\Rules\RequiredWithout;

use function AdityaZanjad\Validator\validate;

#[UsesClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(RequiredWithout::class)]
#[CoversFunction('\AdityaZanjad\Validator\validate')]
final class RequiredWithoutValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator passes when the required fields are present.
     *
     * @return void
     */
    public function testRequiredWithoutAllValidationRulePasses(): void
    {
        $validator = validate([
            'abc'   => '123',
            'def'   => null,
            'ghi'   => 21,
        ], [
            'abc'   =>  'required_without:def|string|min:3',
            'ghi'   =>  'required_without:jkl|numeric|integer|min:12',
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
    }

    /**
     * Assert that the validator fails when the required nested path are not present.
     *
     * @return void
     */
    public function testRequiredWithAllValidationRuleFails(): void
    {
        $validator = validate([
            'abc'   =>  '123',
            'def'   =>  null,
        ], [
            'abc'   =>  'required_without:def|string|min:3',
            'ghi'   =>  'required_without:jkl|numeric|integer|min:12',
        ]);

        $validator->validate();
        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
    }
}
