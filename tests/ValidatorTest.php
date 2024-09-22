<?php

namespace AdityaZanjad\Validator\Tests;

use AdityaZanjad\Validator\Rules\RequiredIf;
use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;


final class ValidatorTest extends TestCase
{
    // public function testEmptyInputData(): void
    // {
    //     try {
    //         $validator = new Validator([], [
    //             'email' => 'required|email'
    //         ]);
    //     } catch (Throwable $e) {
    //         $this->assertInstanceOf(InvalidArgumentException::class, $e);
    //     }
    // }

    // public function testEmptyRules(): void
    // {
    //     try {
    //         $validator = new Validator([
    //             'email' => 'abc@example.com'
    //         ], []);        
    //     } catch (Throwable $e) {
    //         $this->assertInstanceOf(InvalidArgumentException::class, $e);
    //     }
    // }

    // public function testEmailValidation()
    // {
    //     $validator = new Validator([
    //         'email' => 'abc.com'
    //     ], [
    //         'email' => 'required|email'
    //     ]);

    //     $newValidator = new Validator([
    //         'email' => 'abc@email.com'
    //     ], [
    //         'email' => 'required|email'
    //     ]);

    //     $validator->validate();
    //     $this->assertTrue($validator->failed());
    //     $this->assertNotEmpty($validator->allErrors());

    //     $newValidator->validate();
    //     $this->assertFalse($newValidator->failed());
    //     $this->assertEmpty($newValidator->allErrors());
    // }

    public function testRequiredValidation()
    {
        $validator = new Validator([
            'abc' => '',
            'xyz' => 123
        ], [
            'abc' => [new RequiredIf(fn ($attribute, $value) => 'xyz' === 123), 'string']
        ]);

        $validator->validate();
        $this->assertTrue($validator->failed());
        $this->assertNotEmpty($validator->allErrors());
    }
}