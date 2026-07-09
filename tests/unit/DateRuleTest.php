<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AdityaZanjad\Validator\Rules\Date;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Date::class)]
class DateRuleTest extends TestCase
{
    public function testPasses(): void
    {
        $validDates = [
            '2025-01-01',
            '2025-12-31',
            '2000-02-29',      // Leap year
            '2024-02-29',      // Leap year
            '1970-01-01',
            '1900-01-01',
            '9999-12-31',

            '2025/01/01',
            '01/31/2025',
            '31 Jan 2025',
            'January 31, 2025',
            'Jan 31 2025',
            '2025-Jan-31',
            '31-Jan-2025',

            '2025-07-03 12:34:56',
            '2025-07-03T12:34:56',
            '2025-07-03T12:34:56Z',

            '2025-01',

            '   2025-07-03   ',

            new DateTime(),
            new DateTimeImmutable(),
            new DateTime('2025-07-03'),
        ];

        foreach ($validDates as $validDate) {
            $dateValidator = new Date();
            $result = $dateValidator->validate($validDate);

            $this->assertIsBool($result);
            $this->assertTrue($result);
        }
    }

    public function testFails(): void
    {
        $invalidDates = [
            '2025-02-29',
            '2025-02-30',
            '2025-04-31',
            '2025-06-31',
            '2025-09-31',
            '2025-11-31',

            '2023-13-01',
            '2023-00-01',
            '2023-01-00',
            '2023-01-32',

            '1900-02-29',   // Not leap year
            '2100-02-29',   // Not leap year

            '2025',
            'January',
            'Jan',
            '07',
            '--',
            '-',
            '/',

            '',
            ' ',
            '    ',
            "\t",
            "\n",
            "\r\n",

            'hello',
            'banana',
            'foobar',
            'not a date',
            '2025-99-99',
            '999999999',
            '####',
            '@@@',
            '2025-Febtember-31',

            null,
            true,
            false,
            0,
            1,
            123.45,
            [],
            ['2025-01-01'],
            new stdClass(),

            'today',
            'tomorrow',
            'yesterday',
            'next Monday',
            'last Friday',
        ];

        foreach ($invalidDates as $invalidDate) {
            $dateValidator = new Date();
            $result = $dateValidator->validate($invalidDate);

            $this->assertIsBool($result);
            $this->assertFalse($result);
            $this->assertEquals($dateValidator->error(), 'The field :{field} must be a valid date.');
        }
    }
}
