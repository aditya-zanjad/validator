<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Rules\Same;

#[CoversClass(Same::class)]
#[UsesClass(Input::class)]
class SameRuleTest extends TestCase
{
    public function testPassesWhenValuesAreIdentical(): void
    {
        $input = new Input([
            'password' => 'secret123',
            'confirm_password' => 'secret123'
        ]);

        $rule = new Same('password');
        $rule->setInputInstance($input);

        $this->assertTrue($rule->validate('secret123'));
    }

    public function testFailsWhenValuesDiffer(): void
    {
        $input = new Input([
            'password' => 'secret123',
            'confirm_password' => 'different'
        ]);

        $rule = new Same('password');
        $rule->setInputInstance($input);

        $this->assertFalse($rule->validate('different'));
        $this->assertEquals('The value of the field :{field} must be the same as the field password.', $rule->error());
    }
}
