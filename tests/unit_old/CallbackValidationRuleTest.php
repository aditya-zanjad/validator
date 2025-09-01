<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Rules\Callback;
use AdityaZanjad\Validator\Managers\Input;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(Callback::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class CallbackValidationRuleTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'abc' => 'This is a test string!'
        ], [
            'abc' => [
                function (string $field, mixed $value): bool|string {
                    if (!\is_string($value)) {
                        return 'The field :{field} must a string.';
                    }

                    $strLength = \strlen($value);

                    if ($strLength < 5) {
                        return 'The field :{field} must contain at 5 or more characters.';
                    }

                    if ($strLength > 25) {
                        return 'The field :{field} must not contain more than 25 characters.';
                    }

                    return true;
                }
            ],
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
        $this->assertEmpty($validator->errors()->firstOf('abc'));
    }

    /**
     * Assert that the validator succeeds when the given fields are valid.
     *
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $validator = validate([
            'abc' => null,
            'def' => null
        ], [
            'abc' => [
                function (string $field, mixed $value): bool|string {
                    if (!\is_string($value)) {
                        return 'The field :{field} must a string.';
                    }

                    if (\strlen($value) < 5) {
                        return 'The field :{field} must contain at 5 or more characters.';
                    }

                    if (\strlen($value) > 15) {
                        return 'The field :{field} must not contain more than 15 characters.';
                    }

                    return true;
                }
            ],

            'def' => [
                function (string $field, mixed $value): bool|string {
                    if (!\is_array($value)) {
                        return 'The field :{field} must be an array.';
                    }

                    if (empty($value)) {
                        return 'The field :{field} must not be empty.';
                }

                    if (\count($value) < 5) {
                        return 'The field :{field} must contain at least 5 or more elements.';
                    }

                    return true;
                }
            ]
        ]);

        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->errors()->all());
        $this->assertNotEmpty($validator->errors()->firstOf('abc'));
        $this->assertNotEmpty($validator->errors()->firstOf('def'));
    }
}
