<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Validator;
use AdityaZanjad\Validator\Managers\Input;
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
    public function setUp(): void
    {
        # Turn on error reporting
        error_reporting(E_ALL);
        // ...
    }

    /**
     * @return void
     */
    public function testAssertionsPass(): void
    {
        $validator = validate([
            'projects' => [
                [
                    'employees' => [
                        [
                            'id'    =>  1,
                            'name'  =>  'John Doe'
                        ],
                        [
                            'id'    =>  2,
                            'name'  =>  'Jane Smith'
                        ]
                    ],
                    'tasks' => [
                        [
                            'task_id'       =>  'A1',
                            'description'   =>  'Write documentation',
                            'assigned_to'   =>  1
                        ],
                        [
                            'task_id'       =>  'A2',
                            'description'   =>  'Fix bug in feature X',
                            'assigned_to'   =>  2
                        ]
                    ]
                ],
                [
                    'employees' => [
                        [
                            'id'    =>  3,
                            'name'  =>  'Peter Jones'
                        ],
                        [
                            'id'    =>  4,
                            'name'  =>  'Mary Williams'
                        ]
                    ],
                    'tasks' => [
                        [
                            'task_id'       =>  'B1',
                            'description'   =>  'Design new UI',
                            'assigned_to'   =>  3
                        ],
                        [
                            'task_id'       =>  'B2',
                            'description'   =>  'Perform database migration',
                            'assigned_to'   =>  4
                        ]
                    ]
                ],
                [
                    'employees' => [
                        [
                            'id'    =>  5,
                            'name'  =>  'David Miller'
                        ]
                    ],
                    'tasks' => [
                        [
                            'task_id'       =>  'C1',
                            'description'   =>  'Check the CI/CD Pipeline',
                            'assigned_to'   =>  5
                        ],
                        [
                            'task_id'       =>  'C2',
                            'description'   =>  'Deploy to production',
                            'assigned_to'   =>  6
                        ],
                        3 => [
                            'task_id'       =>  'C3',
                            'description'   =>  'Test the production setup',
                            'assigned_to'   =>  7
                        ],
                    ]
                ]
            ]
        ], [
            // Assertions for the top-level structure and nested arrays
            'projects'              =>  'required|array|min:1',
            'projects.*'            =>  'required|array',
            'projects.*.employees'  =>  'required|array|min:1',
            'projects.*.tasks'      =>  'required|array|min:1',

            // Assertions for employee data
            'projects.*.employees.*.id'     =>  'required|integer',
            'projects.*.employees.*.name'   =>  'required|string|min:3',

            // Assertions for task data
            'projects.*.tasks.*.task_id'        =>  'required|string',
            'projects.*.tasks.*.description'    =>  'required|string',
            'projects.*.tasks.*.assigned_to'    =>  'required|integer',

            // General wildcard assertions
            '*'                             =>  'required|array|min:1',
            '*.*'                           =>  'required|array',
            '*.*.*'                         =>  'required|array|min:1',
            'projects.*.employees'          =>  'required|array|min:1',
            'projects.*.employees.*'        =>  'required|array|min:1',
            'projects.*.employees.*.id'     =>  'required|integer|min:1',
            'projects.*.employees.*.name'   =>  'required|string|min:1',
            '*.*.tasks'                     =>  'required|array|min:1',
            '*.*.tasks.*'                   =>  'required|array',
            '*.*.tasks.*.task_id'           =>  'required|string',
            '*.*.tasks.*.description'       =>  'required|string',
            '*.*.tasks.*.assigned_to'       =>  'required|integer',
        ]);

        $this->assertFalse($validator->failed());
        $this->assertEmpty($validator->errors()->all());

        // 'projects' & '*'
        $this->assertEmpty($validator->errors()->firstOf('projects'));

        // 'projects.*' & '*.*'
        $this->assertEmpty($validator->errors()->firstOf('projects.0'));
        $this->assertNull($validator->errors()->firstOf('projects.0'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1'));
        $this->assertNull($validator->errors()->firstOf('projects.1'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1'));
        $this->assertNull($validator->errors()->firstOf('projects.1'));

        // 'projects.*.employees', 'projects.*.tasks' & '*.*.*'
        $this->assertEmpty($validator->errors()->firstOf('projects.0.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks'));

        // 'projects.*.employees.*.id', 'projects.*.employees.*.name'
        $this->assertEmpty($validator->errors()->firstOf('projects.0.employees.0.id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.0.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees.1.name'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1.name'));

        // 'projects.*.tasks.*.task_id', 'projects.*.tasks.*.assigned_to'
        $this->assertEmpty($validator->errors()->firstOf('projects.0.tasks.0.task_id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.0.task_id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.1.assigned_to'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1.assigned_to'));

        // 'projects.*.employees', 'projects.*.tasks' & '*.*.*'
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees'));
        $this->assertEmpty($validator->errors()->firstOf('projects.0.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.employees'));
        $this->assertNull($validator->errors()->firstOf('projects.2.employees'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.tasks'));
        $this->assertNull($validator->errors()->firstOf('projects.2.tasks'));

        // 'projects.*.employees.*.id', 'projects.*.employees.*.name'
        $this->assertEmpty($validator->errors()->firstOf('projects.0.employees.1.id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.1.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.0.employees.1.name'));
        $this->assertNull($validator->errors()->firstOf('projects.0.employees.1.name'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees.0.id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.0.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees.0.name'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.0.name'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees.1.id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.employees.1.name'));
        $this->assertNull($validator->errors()->firstOf('projects.1.employees.1.name'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.employees.0.id'));
        $this->assertNull($validator->errors()->firstOf('projects.2.employees.0.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.employees.0.name'));
        $this->assertNull($validator->errors()->firstOf('projects.2.employees.0.name'));

        // 'projects.*.tasks.*' & '*.*.tasks.*'
        $this->assertEmpty($validator->errors()->firstOf('projects.0.tasks.1.task_id'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.1.task_id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.0.tasks.1.description'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.1.description'));
        $this->assertEmpty($validator->errors()->firstOf('projects.0.tasks.1.assigned_to'));
        $this->assertNull($validator->errors()->firstOf('projects.0.tasks.1.assigned_to'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.0.task_id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.0.task_id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.0.description'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.0.description'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.0.assigned_to'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.0.assigned_to'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.1.task_id'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1.task_id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.1.tasks.1.description'));
        $this->assertNull($validator->errors()->firstOf('projects.1.tasks.1.description'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.tasks.0.task_id'));
        $this->assertNull($validator->errors()->firstOf('projects.2.tasks.0.task_id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.tasks.0.description'));
        $this->assertNull($validator->errors()->firstOf('projects.2.tasks.0.description'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.tasks.0.assigned_to'));
        $this->assertNull($validator->errors()->firstOf('projects.2.tasks.0.assigned_to'));

        // Random assertions
        $this->assertEmpty($validator->errors()->firstOf('projects.0.employees.0.id'));
        $this->assertEmpty($validator->errors()->firstOf('projects.2.tasks.0.task_id'));
    }

    /**
     * @return void
     */
    public function testAssertionsFail(): void
    {
        $data = [
            'projects' => [
                // Project 1: Data with a missing field
                [
                    'employees' => [
                        [
                            'id' => 1,
                            'name' => 'John Doe'
                        ],
                        [
                            'id' => 2,
                            // 'name' => 'Jane Smith'
                        ]
                    ],
                    'tasks' => [
                        [
                            'task_id' => 'A1',
                            'description' => 'Write documentation',
                            // 'assigned_to' => 1
                        ],
                        [
                            'task_id' => 'A2',
                            'description' => 'Fix bug in feature X',
                            // 'assigned_to' is missing
                        ]
                    ]
                ],
                // Project 2: Data with incorrect types and values
                [
                    'employees' => [
                        [
                            'id' => 3,
                            'name' => 'Peter Jones'
                        ],
                        [
                            'id' => 4,
                            'name' => 12345 // name should be a string, but is an integer
                        ]
                    ],
                    'tasks' => [
                        [
                            'task_id' => 'B1',
                            'description' => 'Design new UI',
                            'assigned_to' => 3
                        ],
                        [
                            'task_id' => 'B2',
                            'description' => ['1234! Get on the dance floor!'],
                            'assigned_to' => 4
                        ],
                        // A task with a 'too long' description
                        [
                            'task_id' => 'B3',
                            'description' => 'This is a very long description that exceeds the maximum allowed characters.',
                            'assigned_to' => 5
                        ]
                    ]
                ],
                // Project 3: A project with an empty tasks array
                [
                    'employees' => [],
                    'tasks' => []
                ]
            ]
        ];

        $rules = [
            // // General array rules
            'projects'                          =>  'required|array|min:1',
            'projects.*.employees'              =>  'required|array|min:1',
            'projects.*.tasks'                  =>  'required|array|min:1',

            // // Employee rules
            'projects.*.employees.*.id'         =>  'required|integer',
            'projects.*.employees.*.name'       =>  'required|string|min:5', // Rule set to fail for 'John Doe'
            'projects.1.employees.1.name'       =>  'required|string|max:10', // Rule set to fail for 12345

            // Task rules
            'projects.*.tasks.*.task_id'        =>  'required|string|min:2',
            'projects.*.tasks.*.name'           =>  'required|string',
            'projects.*.tasks.*.description'    =>  'required|string|max:40', // Rule set to fail for the long description
            'projects.*.tasks.*.assigned_to'    =>  'required|integer',

            // Non-existent key
            'projects.*.employees.*.name.first' => 'required|array|string|min:1'
        ];

        $validator = validate($data, $rules);

        // The core assertions you already have
        $this->assertTrue($validator->failed());
        $this->assertFalse(!$validator->failed());
        $this->assertNotEmpty($validator->errors()->all());

        // Assertions for the missing 'assigned_to' key
        $this->assertNotNull($validator->errors()->firstOf('projects.0.tasks.0.assigned_to'));
        $this->assertArrayHasKey('projects.0.tasks.0.assigned_to', $validator->errors()->all());
        $this->assertCount(1, $validator->errors()->of('projects.0.tasks.0.assigned_to'));

        // Assertions for the non-existent 'name.first' key
        $this->assertNotNull($validator->errors()->firstOf('projects.0.employees.0.name.first'));
        $this->assertArrayHasKey('projects.0.employees.0.name.first', $validator->errors()->all());

        // Assertions for the total number of errors
        $this->assertCount(24, $validator->errors()->all());

        $this->assertTrue($validator->failed());
        $this->assertNotNull($validator->errors()->of('projects.0.tasks.0.assigned_to'));
        $this->assertIsArray($validator->errors()->of('projects.0.tasks.0.assigned_to'));
        $this->assertIsString($validator->errors()->firstOf('projects.0.tasks.0.assigned_to'));

        $this->assertTrue($validator->failed());
        $this->assertFalse(!$validator->failed());
        $this->assertNotEmpty($validator->errors()->all());

        // Assertions for the total number of errors
        // Expected failures:
        // 1. projects.0.tasks.1.assigned_to is missing (required)
        // 2. projects.0.employees.0.name is too short (min:5)
        // 3. projects.0.employees.1.name is too short (min:5)
        // 4. projects.1.employees.1.name is not a string (string rule)
        // 5. projects.1.employees.1.name is not a string (max rule - type failure)
        // 6. projects.1.tasks.2.description is too long (max:40)
        // 7. projects.2.employees is empty (min:1)
        // 8. projects.2.tasks is empty (min:1)
        $this->assertCount(24, $validator->errors()->all());

        // Assertions for individual failures
        // Project 1 Failures
        $this->assertNotNull($validator->errors()->firstOf('projects.0.tasks.0.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.0.tasks.1.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.0.tasks.1.assigned_to'));
        $this->assertNotNull($validator->errors()->firstOf('projects.0.employees.1.name'));

        // Project 2 Failures
        $this->assertNotNull($validator->errors()->firstOf('projects.1.tasks.1.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.1.tasks.2.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.1.employees.1.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.1.tasks.2.description'));

        // Project 3 Failures
        $this->assertNotNull($validator->errors()->firstOf('projects.2.tasks.0.name'));
        $this->assertNotNull($validator->errors()->firstOf('projects.2.tasks.0.assigned_to'));
        $this->assertNotNull($validator->errors()->firstOf('projects.2.employees'));
        $this->assertNotNull($validator->errors()->firstOf('projects.2.tasks'));
    }
}
