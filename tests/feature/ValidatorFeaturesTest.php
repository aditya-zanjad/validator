<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Gt;
use AdityaZanjad\Validator\Rules\In;
use AdityaZanjad\Validator\Rules\Gte;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Error;
use AdityaZanjad\Validator\Managers\Input;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\TypeArray;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Rules\TypeString;
use AdityaZanjad\Validator\Rules\TypeInteger;
use PHPUnit\Framework\Attributes\CoversClass;
use AdityaZanjad\Validator\Rules\RequiredWith;

#[UsesClass(In::class)]
#[UsesClass(Gte::class)]
#[UsesClass(Error::class)]
#[UsesClass(Input::class)]
#[UsesClass(Required::class)]
#[UsesClass(TypeArray::class)]
#[UsesClass(TypeString::class)]
#[CoversClass(Validator::class)]
#[UsesClass(TypeInteger::class)]
#[UsesClass(RequiredWith::class)]
class ValidatorFeaturesTest extends TestCase
{
    /**
     * @return void
     */
    public function testPasses(): void
    {
        $data       =   $this->makeDataAndRulesForPassingCase();
        $input      =   new Input($data['input']);
        $errors     =   new Error();
        $messages   =   [];
        $validator  =   new Validator($input, $data['rules'], $errors, $messages);

        $validator->validate();

        // Assert that the the validation succeeded.
        $this->assertIsBool($validator->failed());
        $this->assertFalse($validator->failed());

        // Assert that if the validation errors array is empty.
        $this->assertIsArray($validator->errors()->all());
        $this->assertEmpty($validator->errors()->all());

        // Assert that there are no validation errors for the given fields.
        # org
        $this->assertNull($validator->errors()->of('org'));
        $this->assertNull($validator->errors()->firstOf('org'));

        # org.id
        $this->assertNull($validator->errors()->of('org.id'));
        $this->assertNull($validator->errors()->firstOf('org.id'));

        # org.name
        $this->assertNull($validator->errors()->of('org.name'));
        $this->assertNull($validator->errors()->firstOf('org.name'));

        # payment
        $this->assertNull($validator->errors()->of('payment'));
        $this->assertNull($validator->errors()->firstOf('payment'));

        # payment.status
        $this->assertNull($validator->errors()->of('payment.status'));
        $this->assertNull($validator->errors()->firstOf('payment.status'));

        # projects
        $this->assertNull($validator->errors()->of('projects'));
        $this->assertNull($validator->errors()->firstOf('projects'));

        /**
         * Make sure that there are no validation errors for such path i.e. we 
         * want to make sure that the wildcard path was resolved to its 
         * actual corresponding paths.
         */
        # projects.*
        $this->assertNull($validator->errors()->of('projects.*'));
        $this->assertNull($validator->errors()->firstOf('projects.*'));

        # projects.0
        $this->assertNull($validator->errors()->of('projects.0'));
        $this->assertNull($validator->errors()->firstOf('projects.0'));

        # projects.1
        $this->assertNull($validator->errors()->of('projects.1'));
        $this->assertNull($validator->errors()->firstOf('projects.1'));

        # projects.*.employees
        $this->assertNull($validator->errors()->of('projects.*.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.*.employees'));

        # projects.0.employees
        $this->assertNull($validator->errors()->of('projects.0.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees'));

        # projects.1.employees
        $this->assertNull($validator->errors()->of('projects.1.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees'));

        # projects.*.tasks
        $this->assertNull($validator->errors()->of('projects.*.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.*.tasks'));

        # projects.0.tasks
        $this->assertNull($validator->errors()->of('projects.0.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks'));

        # projects.1.tasks
        $this->assertNull($validator->errors()->of('projects.1.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks'));

        # projects.*.employees.*
        $this->assertNull($validator->errors()->of('projects.*.employees.*'));
        $this->assertNull($validator->errors()->firstOf('projects.*.employees.*'));

        # projects.0.employees.0
        $this->assertNull($validator->errors()->of('projects.0.employees.0'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.0'));

        # projects.1.employees.1
        $this->assertNull($validator->errors()->of('projects.1.employees.1'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1'));

        # projects.*.tasks.*
        $this->assertNull($validator->errors()->of('projects.*.tasks.*'));
        $this->assertNull($validator->errors()->firstOf('projects.*.tasks.*'));

        # projects.0.tasks.0
        $this->assertNull($validator->errors()->of('projects.0.tasks.0'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.0'));

        # projects.1.tasks.1
        $this->assertNull($validator->errors()->of('projects.1.tasks.1'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1'));

        # projects.*.employees.*.id
        $this->assertNull($validator->errors()->of('projects.*.employees.*.id'));
        $this->assertNull($validator->errors()->firstOf('projects.*.employees.*.id'));

        # projects.0.employees.0.id
        $this->assertNull($validator->errors()->of('projects.0.employees.0.id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.0.id'));

        # projects.1.employees.1.id
        $this->assertNull($validator->errors()->of('projects.1.employees.1.id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1.id'));

        # projects.*.employees.*.name
        $this->assertNull($validator->errors()->of('projects.*.employees.*.name'));
        $this->assertNull($validator->errors()->firstOf('projects.*.employees.*.name'));

        # projects.0.employees.0.name
        $this->assertNull($validator->errors()->of('projects.0.employees.0.name'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.0.name'));

        # projects.1.employees.1.name
        $this->assertNull($validator->errors()->of('projects.1.employees.1.name'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1.name'));

        # projects.*.tasks.*.id
        $this->assertNull($validator->errors()->of('projects.*.tasks.*.id'));
        $this->assertNull($validator->errors()->firstOf('projects.*.tasks.*.id'));

        # projects.0.tasks.0.id
        $this->assertNull($validator->errors()->of('projects.0.tasks.0.id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.0.id'));

        # projects.1.tasks.1.id
        $this->assertNull($validator->errors()->of('projects.1.tasks.1.id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1.id'));

        # projects.*.tasks.*.name
        $this->assertNull($validator->errors()->of('projects.*.tasks.*.name'));
        $this->assertNull($validator->errors()->firstOf('projects.*.tasks.*.name'));

        # projects.0.tasks.0.name
        $this->assertNull($validator->errors()->of('projects.0.tasks.0.name'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.0.name'));

        # projects.1.tasks.1.name
        $this->assertNull($validator->errors()->of('projects.1.tasks.1.name'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1.name'));
    }

    /**
     * @return void
     */
    public function testEmptyRulesArrayProhibited(): void
    {
        $input      =   new Input([]);
        $rules      =   [];
        $errors     =   new Error();
        $messages   =   [];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The parameter [rules] must not be empty. You must provide at least one validation rule.");
        new Validator($input, $rules, $errors, $messages);
    }

    /**
     * @return void
     */
    public function testInvalidRulesProhibited(): void
    {
        $input      =   new Input(['first' => 'abc']);
        $rules      =   [new stdClass()];
        $errors     =   new Error();
        $messages   =   [];
        $validator  =   new Validator($input, $rules, $errors, $messages);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("[Developer][Exception]: The field [0] must have validation rules specified in [STRING] or [ARRAY] format.");
        $validator->validate();
    }

    /**
     * @return array
     */
    protected function makeDataAndRulesForPassingCase(): array
    {
        return [
            'input' => [
                'org' => [
                    'id'    =>  1,
                    'name'  =>  'Organization One'
                ],

                'payment' => [
                    'status' => 'DONE'
                ],

                'projects' => [
                    [
                        'employees' => [
                            [
                                'id'    =>  1,
                                'name'  =>  'Employee One'
                            ],
                            [
                                'id'    =>  2,
                                'name'  =>  'Employee Two'
                            ],
                            [
                                'id'    =>  3,
                                'name'  =>  'Employee Three'
                            ],
                        ],

                        'tasks' => [
                            [
                                'id'    =>  1,
                                'name'  =>  'Task One'
                            ],
                            [
                                'id'    =>  2,
                                'name'  =>  'Task Two'
                            ],
                            [
                                'id'    =>  3,
                                'name'  =>  'Task Three'
                            ],
                        ]
                    ]
                ]
            ],

            'rules' => [
                'org'                           =>  'required|array|size:2',
                'org.id'                        =>  'required_with:org|integer|gt:0',
                'org.name'                      =>  'required_with:org|string|min:2',
                'payment'                       =>  ['required', 'array', 'min:1'],
                'payment.status'                =>  ['required', 'string', 'in: PENDING, INITIATED, DONE'],
                'projects'                      =>  'required|array|min:1',
                'projects.*'                    =>  'required_with:projects|array|min:2',
                'projects.*.employees'          =>  'required|array|min:1',
                'projects.*.employees.*'        =>  'required|array|min:2',
                'projects.*.employees.*.id'     =>  'required|integer|gt:0',
                'projects.*.employees.*.name'   =>  'required|string|min:2',
                'projects.*.tasks'              =>  'required|array|min:1',
                'projects.*.tasks.*'            =>  'required|array|min:2',
                'projects.*.tasks.*.id'         =>  [new Required(), 'integer', new Gt(0)],

                'projects.*.tasks.*.name' => [
                    new Required(),
                    function (mixed $value) {
                        if (!\is_string($value)) {
                            return 'The field :{field} must be a string';
                        }

                        if (\strlen($value) < 2) {
                            return 'The field :{field} must contain at least 2 characters.';
                        }

                        return true;
                    }
                ],
            ]
        ];
    }
}
