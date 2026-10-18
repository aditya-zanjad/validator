<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqIf;

#[CoversClass(ReqIf::class)]
#[UsesClass(Input::class)]
class ReqIfRuleTest extends TestCase
{
    public function testPassesWhenDependencyEqualsValueAndCurrentValueProvided(): void
    {
        $input = new Input(['role' => 'admin']);
        $rule = new ReqIf('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('secret_admin_token'));
    }

    public function testFailsWhenDependencyEqualsValueAndCurrentValueNull(): void
    {
        $input = new Input(['role' => 'admin']);
        $rule = new ReqIf('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required when the field role is set.', $rule->error());
    }

    public function testPassesWhenDependencyDoesNotEqualValueAndCurrentValueNull(): void
    {
        $input = new Input(['role' => 'guest']);
        $rule = new ReqIf('role', 'admin');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testInvalidConstructorArgumentsThrowException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_if [AdityaZanjad\Validator\Rules\ReqIf] requires at least two parameters.');
        new ReqIf('role');
    }
}
