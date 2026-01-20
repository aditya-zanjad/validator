<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Str;
use AdityaZanjad\Validator\Base\AbstractRule;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Str::class)]
#[CoversClass(AbstractRule::class)]
final class StrRuleTest extends TestCase
{
    public function testPasses()
    {
        $strs = [
            '',
            '1234',
            '!!! Hello World !!!',
            'प्सफलता की कुंजी केवल कठिन परिश्रम नहीं, बल्कि सही दिशा में किया गया निरंतर प्रयास है।',
            '強さは生であり、弱さは死である。',
            '力量即生命，软弱即死亡。',
            'القوة هي الحياة، والضعف هو الموت.',
            'Сила — это жизнь, слабость — это смерть.',
            'Stärke ist Leben, Schwäche ist Tod.',
            'A força é a vida, a fraqueza é a morte',
            '1234! Get on the dance floor!'
        ];

        foreach ($strs as $str) {
            $rule = new Str();
            $this->assertTrue($rule->validate($str));
        }
    }

    public function testFails()
    {
        $strs = [
            123,
            123.123,
            true,
            ['This is a string inside an array!'],
            (object) [],
        ];

        foreach ($strs as $str) {
            $rule = new Str();
            $this->assertFalse($rule->validate($str));
            $this->assertEquals($rule->error(), 'The field :{field} must be a string');
        }
    }
}
