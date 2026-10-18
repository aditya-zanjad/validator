<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\Callback;

#[CoversClass(Callback::class)]
#[UsesClass(Input::class)]
class CallbackRuleTest extends TestCase
{
    public function testPassesWhenCallbackReturnsTrue(): void
    {
        $rule = new Callback(fn () => true);
        $rule->setFieldName('test_field');
        $rule->setInputInstance(new Input([]));
        $this->assertTrue($rule->validate('Hello World!'));
    }

    public function testFailsWhenCallbackReturnsFalse(): void
    {
        $rule = new Callback(function ($value) {
            return false;
        });
        $rule->setFieldName('test_field');
        $rule->setInputInstance(new Input([]));

        $this->assertFalse($rule->validate('invalid'));
    }

    public function testFailsWithCustomMessageWhenCallbackReturnsString(): void
    {
        $rule = new Callback(function ($value) {
            return 'Custom error message';
        });
        $rule->setFieldName('test_field');
        $rule->setInputInstance(new Input([]));

        $this->assertFalse($rule->validate('invalid'));
        $this->assertEquals('Custom error message', $rule->error());
    }

    public function testNonBooleanAndNonStringThrowsException(): void
    {
        $rule = new Callback(function ($value) {
            return 123;
        });
        $rule->setFieldName('test_field');
        $rule->setInputInstance(new Input([]));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The callback validation rule must always return either a STRING or a BOOLEAN value.');
        $rule->validate('anything');
    }
}
