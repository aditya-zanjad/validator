<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqWith;

#[CoversClass(ReqWith::class)]
#[UsesClass(Input::class)]
class ReqWithRuleTest extends TestCase
{
    public function testPassesWhenDependencyFieldPresentAndValueProvided(): void
    {
        $input = new Input(['first_name' => 'John', 'last_name' => 'Doe']);
        $rule = new ReqWith('first_name');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('Doe'));
    }

    public function testPassesWhenDependencyFieldNotPresent(): void
    {
        $input = new Input([]);
        $rule = new ReqWith('first_name');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testFailsWhenDependencyFieldPresentAndValueNull(): void
    {
        $input = new Input(['first_name' => 'John']);
        $rule = new ReqWith('first_name');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required with the field first_name', $rule->error());
    }

    public function testEmptyDependenciesThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_with [AdityaZanjad\Validator\Rules\ReqWith] requires at least one parameter.');
        new ReqWith();
    }
}
