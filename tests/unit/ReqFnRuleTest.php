<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\ReqFn;

#[CoversClass(ReqFn::class)]
#[UsesClass(Input::class)]
class ReqFnRuleTest extends TestCase
{
    public function testPassesWhenCallbackReturnsTrue(): void
    {
        $input = new Input(['key' => 'val']);
        $rule = new ReqFn(function ($value, $field, $input) {
            return !empty($value);
        });
        $rule->setFieldName('my_field');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('not-empty'));
    }

    public function testFailsWhenCallbackReturnsFalse(): void
    {
        $input = new Input([]);
        $rule = new ReqFn(function ($value, $field, $input) {
            return false;
        });
        $rule->setFieldName('my_field');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
    }

    public function testFailsWithCustomMessageWhenCallbackReturnsString(): void
    {
        $input = new Input([]);
        $rule = new ReqFn(function ($value, $field, $input) {
            return 'Custom required error message';
        });
        $rule->setFieldName('my_field');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate(null));
        $this->assertEquals('Custom required error message', $rule->error());
    }

    public function testInvalidReturnTypeThrowsException(): void
    {
        $input = new Input([]);
        $rule = new ReqFn(function ($value, $field, $input) {
            return 123;
        });
        $rule->setFieldName('my_field');
        $rule->setInputInstance($input);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('[Developer][Exception]: The callback passed to the rule required_fn [AdityaZanjad\Validator\Rules\ReqFn] must return either a STRING or a BOOLEAN value.');
        $rule->validate(null);
    }
}
