<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\TypeInteger;

#[CoversClass(TypeInteger::class)]
class IntegerValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            'first'     =>  0,
            'second'    =>  1,
            'third'     =>  -1,
            'fourth'    =>  '0',
            'fifth'     =>  '1',
            'sixth'     =>  '-1',
            'seventh'   =>  10000000,
            'eigth'     =>  '-100000000',
            'ninth'     =>  0.0,
            'tenth'     =>  1.0
        ];

        foreach ($data as $value) {
            $rule   =   new TypeInteger();
            $result =   $rule->check($value);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    /**
     * @return void
     */
    public function testFails(): void
    {
        $tmpFile = tmpfile();
        fwrite($tmpFile, 'This is a temp text inside a temp file!');

        $data = [
            'first'         =>  -1.01,
            'second'        =>  '0.01',
            'third'         =>  '1.01',
            'fourth'        =>  '-1.01',
            'fifth'         =>  10000000.112364671,
            'sixth'         =>  '-100000000.1246123',
            'seventh'       =>  'This is a test string! This is not an integer!',
            'eigth'         =>  new \ArrayObject(),
            'ninth'         =>  new \stdClass(),
            'tenth'         =>  $tmpFile,
            'eleventh'      =>  true,
            'twelfth'       =>  false,
            'thirteenth'    =>  ['This is a test array!']
        ];

        foreach ($data as $value) {
            $rule   =   new TypeInteger();
            $result =   $rule->check($value);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be an integer value.');
        }

        fclose($tmpFile);
    }
}
