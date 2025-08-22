<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Fluents\Input;
use AdityaZanjad\Validator\Rules\TypeString;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;

use function AdityaZanjad\Validator\Presets\validate;

#[CoversClass(Validator::class)]
#[CoversClass(Error::class)]
#[CoversClass(Input::class)]
#[CoversClass(TypeString::class)]
#[CoversFunction('\AdityaZanjad\Validator\Presets\validate')]
final class WildCardsParametersTest extends TestCase
{
    /**
     * Assert that the validator fails when the given string is an invalid string.
     *
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'projects' => [
                [
                    'employees' => [
                        [
                            'id' => 1,
                            'name' => 'Employee One'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Employee Two'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Employee Three'
                        ]
                    ]
                ]
            ]
        ], [
            // '*.*'                           =>  'array|min:1',
            // '*.*.*'                         =>  'array|min:2',
            
            '*'                             =>  'required|array|min:1',
            'projects'                      =>  ['max:3'],
            'projects.*.employees'          =>  'required|array|min:2',
            'projects.*.employees.*.id'     =>  'required|integer',
            'projects.*.employees.*.string' =>  'required|string|min:3',
            'projects.*.employees.id'       =>  'required|numeric|integer'
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());
    }
}
