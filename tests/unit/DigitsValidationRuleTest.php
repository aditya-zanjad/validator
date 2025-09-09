<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Digits;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Digits::class)]
class DigitsValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            [
                'value'     =>  0,
                'digits'    =>  1,
            ],
            [
                'value'     =>  12345,
                'digits'    =>  '5'
            ],
            [
                'value'     =>  1231123781.1231231,
                'digits'    =>  10
            ],
            [
                'value'     =>  '9782392',
                'digits'    =>  '7'
            ],
            [
                'value'     =>  '9234324234723.234234',
                'digits'    =>  13
            ],
            [
                'value'     =>  -123141,
                'digits'    =>  6
            ],
            [
                'value'     =>  -674390435.2543534,
                'digits'    =>  '9'
            ]
        ];

        foreach ($data as $num) {
            $rule   =   new Digits($num['digits']);
            $result =   $rule->check($num['value']);

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
                'value'     =>  '123123',
                'digits'    =>  '9'
            ],
            [
                'value'     =>  '32579230419234.2342343242',
                'digits'    =>  '9'
            ],
            [
                'value'     =>  null,
                'digits'    =>  '5'
            ],
            [
                'value'     =>  true,
                'digits'    =>  10
            ],
            [
                'value'     =>  'FALSE',
                'digits'    =>  '7'
            ],
            [
                'value'     =>  'This is a test string!',
                'digits'    =>  13
            ],
            [
                'value'     =>  new ArrayObject(),
                'digits'    =>  6
            ],
            [
                'value'     =>  new stdClass(),
                'digits'    =>  '9'
            ],
            [
                'value'     =>  ['This is a test string inside a test array!'],
                'digits'    =>  '1259'
            ]
        ];

        foreach ($data as $num) {
            $rule   =   new Digits($num['digits']);
            $result =   $rule->check($num['value']);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), "The field :{field} must contain exactly {$num['digits']} digits.");
        }
    }

    /**
     * @return void
     */
    public function testAbortsOnInvalidParameters(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The parameter passed to the validation rule [digits] must be a valid integer.');
        new Digits('string-1234');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The parameter passed to the validation rule [digits] must be greater than 0.");
        new Digits('-23');
    }
}
