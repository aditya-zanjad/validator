<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\TypeArray;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TypeArray::class)]
final class ArrayValidationRuleTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data = [
            'first'     =>  [],
            'second'    =>  ['1234!', 'Get on the dance floor!'],
            'third'     =>  new \ArrayObject()
        ];

        foreach ($data as $value) {
            $rule   =   new TypeArray();
            $result =   $rule->check($value); 

            $this->assertIsBool($result);
            $this->assertTrue($result);
            $this->assertEquals($result, true);
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
            'first'     =>  12345,
            'second'    =>  4575.123421,
            'third'     =>  'This is a test string',
            'fourth'    =>  new \stdClass(),
            'fifth'     =>  true,
            'sixth'     =>  $tmpFile,
            'seventh'   =>  null
        ];

        foreach ($data as $value) {
            $rule   =   new TypeArray();
            $result =   $rule->check($value); 

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($rule->message(), 'The field :{field} must be an array.');
        }

        fclose($tmpFile);
    }
}
