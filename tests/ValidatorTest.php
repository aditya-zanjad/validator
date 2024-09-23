<?php

namespace AdityaZanjad\Validator\Tests;

use AdityaZanjad\Validator\Rules\RequiredIf;
use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use InvalidArgumentException;
use Throwable;

final class ValidatorTest extends TestCase
{
    protected Validator $validator;

    public function setUp(): void
    {
        $this->validator = new Validator(
            $this->makeInputData(),
            $this->makeValidationRules()
        );
    }

    protected function makeInputData()
    {
        return [
            'valid_email'                   =>  'abc@email.com',
            'invalid_email'                 =>  'abc.com',
            'required_attribute_missing'    =>  '',
            'required_attribute_present'    =>  'present',
            'min_rule_value'                =>  100,
        ];
    }

    protected function makeValidationRules()
    {
        return [
            'valid_email' => 'required|email',
            'invalid_email' => 'required|email',
            'required_attribute_missing' => 'required',
            'required_attribute_present' => 'required',
            'min_rule_value' => 'min:1000'
        ];
    }

    public function testValidationFails()
    {
        $this->validator->validate();
        $this->assertTrue($this->validator->failed());
        $this->assertNotEmpty($this->validator->allErrors());
    }
}