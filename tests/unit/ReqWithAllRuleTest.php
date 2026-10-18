<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqWithAll;

#[CoversClass(ReqWithAll::class)]
#[UsesClass(Input::class)]
class ReqWithAllRuleTest extends TestCase
{
    public function testPassesWhenAllDependenciesPresentAndValueProvided(): void
    {
        $input = new Input(['f1' => 'v1', 'f2' => 'v2']);
        $rule = new ReqWithAll('f1', 'f2');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('current_value'));
    }

    public function testPassesWhenNotAllDependenciesPresentEvenIfValueNull(): void
    {
        $input = new Input(['f1' => 'v1']); // f2 is missing
        $rule = new ReqWithAll('f1', 'f2');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testFailsWhenAllDependenciesPresentAndValueNull(): void
    {
        $input = new Input(['f1' => 'v1', 'f2' => 'v2']);
        $rule = new ReqWithAll('f1', 'f2');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required with the field f2', $rule->error());
    }

    public function testEmptyDependenciesThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_with [AdityaZanjad\Validator\Rules\ReqWithAll] requires at least one parameter.');
        new ReqWithAll();
    }
}
