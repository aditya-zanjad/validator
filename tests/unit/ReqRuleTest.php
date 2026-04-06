<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\Req;
use AdityaZanjad\Validator\Validator;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\CoversClass;

#[UsesClass(Input::class)]
#[UsesClass(Validator::class)]
#[CoversClass(Req::class)]
class ReqRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $inputs = new Input([
            'abc'   => '123',
            'def'   => 'Hello World!',
            'ghi'   =>  [1, 2, 3],
            'jkl'   =>  (object) ['A', 'E', 'I', 'O', 'U'],
            'mno'   =>  [null],
            'pqr'   =>  'null'
        ]);

        $rules = [
            'abc'   =>  'req',
            'def'   =>  'req',
            'ghi'   =>  'req',
            'jkl'   =>  'req',
            'mno'   =>  'req',
            'pqr'   =>  'req',
        ];

        $validator = new Validator($inputs, $rules);
        $validator->validate();

        $this->assertNull($validator->errors()->firstOf('abc'));
        $this->assertNull($validator->errors()->firstOf('def'));
        $this->assertNull($validator->errors()->firstOf('ghi'));
        $this->assertNull($validator->errors()->firstOf('jkl'));
        $this->assertNull($validator->errors()->firstOf('mno'));
        $this->assertNull($validator->errors()->firstOf('pqr'));
        $this->assertFalse($validator->failed());
    }

    public function testFails(): void
    {
        $inputs = new Input([
            'abc'   =>  '',
            'def'   =>  [],
            'ghi'   =>  null,
        ]);

        $rules = [
            'abc'   =>  'req',
            'def'   =>  'req',
            'ghi'   =>  'req',
            'jkl'   =>  'req'
        ];

        $validator = new Validator($inputs, $rules);
        $validator->validate();

        $this->assertIsString($validator->errors()->firstOf('abc'));
        $this->assertIsString($validator->errors()->firstOf('def'));
        $this->assertIsString($validator->errors()->firstOf('ghi'));
        $this->assertIsString($validator->errors()->firstOf('jkl'));
        $this->assertTrue($validator->failed());
    }
}
