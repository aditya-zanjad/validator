<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqUnless;

#[CoversClass(ReqUnless::class)]
#[UsesClass(Input::class)]
class ReqUnlessRuleTest extends TestCase
{
    public function testPassesWhenDependencyMatchesSpecifiedValueEvenIfCurrentValueNull(): void
    {
        $input = new Input(['role' => 'admin']);
        $rule = new ReqUnless('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testFailsWhenDependencyDoesNotMatchSpecifiedValueAndCurrentValueNull(): void
    {
        $input = new Input(['role' => 'member']);
        $rule = new ReqUnless('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required unless the field role is in the specified values.', $rule->error());
    }

    public function testPassesWhenDependencyDoesNotMatchSpecifiedValueButCurrentValueProvided(): void
    {
        $input = new Input(['role' => 'member']);
        $rule = new ReqUnless('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('provided_value'));
    }

    public function testInvalidConstructorArgumentsThrowException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_unless [AdityaZanjad\Validator\Rules\ReqUnless] requires at least two parameters.');
        new ReqUnless('role');
    }
}
