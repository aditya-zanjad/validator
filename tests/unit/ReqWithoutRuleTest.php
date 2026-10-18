<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqWithout;

#[CoversClass(ReqWithout::class)]
#[UsesClass(Input::class)]
class ReqWithoutRuleTest extends TestCase
{
    public function testPassesWhenDependencyFieldPresentAndValueNull(): void
    {
        $input = new Input(['backup_email' => 'backup@example.com']);
        $rule = new ReqWithout('backup_email');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate(null));
    }

    public function testFailsWhenDependencyFieldNotPresentAndValueNull(): void
    {
        $input = new Input([]);
        $rule = new ReqWithout('backup_email');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('The field :{field} is required without the field backup_email', $rule->error());
    }

    public function testPassesWhenValueProvidedEvenIfDependencyFieldNotPresent(): void
    {
        $input = new Input([]);
        $rule = new ReqWithout('backup_email');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('main@example.com'));
    }

    public function testEmptyDependenciesThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The rule required_without [AdityaZanjad\Validator\Rules\ReqWithout] requires at least one parameter.');
        new ReqWithout();
    }
}
