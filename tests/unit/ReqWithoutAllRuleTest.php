<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqWithoutAll;

#[CoversClass(ReqWithoutAll::class)]
#[UsesClass(Input::class)]
class ReqWithoutAllRuleTest extends TestCase
{
    public function testPassesWhenAtLeastOneDependencyPresentAndValueNull(): void
    {
        $input = new Input(['phone' => '123456789']);
        $rule = new ReqWithoutAll('phone', 'email');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testFailsWhenAllDependenciesMissingAndValueNull(): void
    {
        $input = new Input([]);
        $rule = new ReqWithoutAll('phone', 'email');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required when none of phone, email are present.', $rule->error());
    }

    public function testPassesWhenValueProvidedEvenIfAllDependenciesMissing(): void
    {
        $input = new Input([]);
        $rule = new ReqWithoutAll('phone', 'email');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('provided_contact'));
    }

    public function testEmptyDependenciesThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_without_all [AdityaZanjad\Validator\Rules\ReqWithoutAll] requires at least one parameter.');
        new ReqWithoutAll();
    }
}
