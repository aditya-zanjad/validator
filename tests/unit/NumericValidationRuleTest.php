<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Numeric;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Numeric::class)]
class NumericValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            0,
            0.0,
            1.0,
            1.01,
            121247918,
            -121247918,
            '0',
            '0.0',
            '1.0',
            '1.01',
            '121247918',
            '-121247918',
            124124.12312412,
            '124124.12312412'
        ];

        foreach ($data as $value) {
            $rule   = new Numeric();
            $result = $rule->check($value);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    /**
     * @return void
     */
    public function testFails(): void
    {
        $data = [
            'This is a test string!',
            '1234! Get on the dance floor!',
            new ArrayObject(),
            true,
            false,
            'true',
            'FALSE',
            '0.1.234',
            ['This is a test element of a test array'],
            new stdClass()
        ];

        foreach ($data as $value) {
            $rule   =   new Numeric();
            $result =   $rule->check($value);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be a valid number.');
        }
    }
}