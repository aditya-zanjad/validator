<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\DigitsLt;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DigitsLt::class)]
class DigitsLessThanValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            [
                'num'       =>  0,
                'digits'    =>  2
            ],
            [
                'num'       =>  '123123.213',
                'digits'    =>  7,
            ],
            [
                'num'       =>  545789453.234234,
                'digits'    =>  10,
            ],
            [
                'num'       =>  '12359234432432',
                'digits'    =>  15
            ],
            [
                'num'       =>  352342392348234,
                'digits'    =>  16
            ]
        ];

        foreach ($data as $option) {
            $rule   =   new DigitsLt($option['digits']);
            $result =   $rule->check($option['num']);

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
            [
                'num'       =>  'dafadfw3578934',
                'digits'    =>  2
            ],
            [
                'num'       =>  '3247829349234',
                'digits'    =>  2
            ],
            [
                'num'       =>  123123123,
                'digits'    =>  5
            ],
            [
                'num'       =>  1234,
                'digits'    =>  4
            ],
            [
                'num'       =>  ['This is a test string inside a test array!'],
                'digits'    =>  4
            ],
            [
                'num'       =>  true,
                'digits'    =>  2,
            ],
        ];

        foreach ($data as $option) {
            $rule   =   new DigitsLt($option['digits']);
            $result =   $rule->check($option['num']);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), "The field :{field} must contain less than {$option['digits']} digits.");
        }
    }

    /**
     * @return void
     */
    public function testAbortsOnInvalidParameter(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter passed to the validation rule [digits_lt] must be a valid integer.');
        new DigitsLt('string-1234');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The value of the parameter passed to the validation rule [digits_lt] must not be less than 2.");
        new DigitsLt('-23');
    }
}