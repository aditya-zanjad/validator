<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\TypeBoolean;

#[CoversClass(TypeBoolean::class)]
final class BooleanValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            'first'         =>  true,
            'second'        =>  false,
            'third'         =>  'true',
            'fourth'        =>  'false',
            'fifth'         =>  'no',
            'sixth'         =>  'yes',
            'seventh'       =>  'on',
            'eight'         =>  'off',
            'ninth'         =>  0,
            'tenth'         =>  1,
            'eleventh'      =>  '0',
            'twelth'        =>  '1',
            'thirteenth'    =>  'TRUE',
            'fourteenth'    =>  'FALSE',
            'sixteenth'     =>  'ON',
            'seventeenth'   =>  'OFF',
            'eighteenth'    =>  'NO',
            'ninteenth'     =>  'YES'
        ];

        foreach ($data as $key => $value) {
            $rule   =   new TypeBoolean();
            $result =   $rule->check($value);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    /**
     * @return void
     */
    public function testAllowedValuesPass(): void
    {
        $data = [
            'first'     =>  true,
            'second'    =>  false,
            'third'     =>  0,
            'fourth'    =>  1,
            'fifth'     =>  'true',
            'sixth'     =>  'false',
            'seventh'   =>  '0',
            'eighth'    =>  '1',
            'ninth'     =>  'TRUE',
            'tenth'     =>  'FALSE',
            'eleventh'  =>  'TrUe',
            'twelfth'   =>  'FAlse',
        ];

        foreach ($data as $value) {
            $rule   = new TypeBoolean('true/false', '0/1');
            $result = $rule->check($value);

            $this->assertIsBool($result);
            $this->assertTrue($result);
            $this->assertEquals($result, true);
        }

        $data = [
            'first'     =>  'on',
            'second'    =>  'OfF',
            'third'     =>  'true',
            'fourth'    =>  'FALSE',
            'fifth'     =>  'no',
            'sixth'     =>  'YES'
        ];

        foreach ($data as $value) {
            $rule   = new TypeBoolean('str_true/false', 'off/on', 'yes/no');
            $result = $rule->check($value);

            $this->assertIsBool($result);
            $this->assertTrue($result);
            $this->assertEquals($result, true);
        }
    }

    /**
     * @return void
     */
    public function testNotAllowedValuesFail(): void
    {
        $data = [
            'first'     =>  'on',
            'second'    =>  'off',
            'third'     =>  'no',
            'fourth'    =>  'yes',
            'fifth'     =>  'true',
            'sixth'     =>  'false',
            'seventh'   =>  '0',
            'eight'     =>  '1'
        ];

        foreach ($data as $value) {
            $rule   = new TypeBoolean('bool_true/false', 'int_0/1');
            $result = $rule->check($value);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be a boolean value.');
        }
    }
    
    /**
     * @return void
     */
    public function testFailsOnInvalidParameters(): void
    {
        $data = [
            'first'     =>  true,
            'second'    =>  false
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The validation rule [boolean] requires its each parameter to be one of these: "bool_true/false", "int_0/1", "str_true/false", "str_0/1", "on/off", "off/on", "yes/no", "no/yes"');

        foreach ($data as $value) {
            $rule = new TypeBoolean('valid/invalid', 'truth/false');
            $rule->check($value);
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
            'first'         =>  12345,
            'second'        =>  4575.123421,
            'third'         =>  ['This is a test string'],
            'fourth'        =>  new \stdClass(),
            'fifth'         =>  '23456',
            'sixth'         =>  '12152135.sdfad',
            'seventh'       =>  'This is a test string',
            'tenth'         =>  $tmpFile,
            'eleventh'      =>  null,
        ];

        foreach ($data as $key => $value) {
            $rule   =   new TypeBoolean();
            $result =   $rule->check($value);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be a boolean value.');
        }

        fclose($tmpFile);
    }
}
