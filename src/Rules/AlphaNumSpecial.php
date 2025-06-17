<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\str_contains_v2;

/**
 * @version 1.0
 */
class AlphaNumSpecial extends AbstractRule
{
    /**
     * A list of numbers to check the given input string against for the presence of at least one number.
     *
     * @var array<int, string> $numbers
     */
    protected array $numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    /**
     * A list of special characters to check the given input string against for the presence of at least one special character.
     *
     * @var array<int, string> $symbols
     */
    protected array $symbols = ['`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '-', '_', '=', '+', '\\', '|', ';', ':', '\'', '"', ',', '<', '.', '>', '/', '?'];

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be a string consisting of alphabets, numbers & special characters.';
        }

        if (function_exists('preg_match') && preg_match('/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{}|;:\'",.<>\/?`~]+$/', $value) === false) {
            return 'The field :{field} must be a string consisting of alphabets, numbers & special characters.';
        }

        $valueHasAtLeastOneNumber = false;

        foreach ($this->numbers as $number) {
            if (str_contains_v2($value, $number)) {
                $valueHasAtLeastOneNumber = true;
                break;
            }
        }

        if (!$valueHasAtLeastOneNumber) {
            return 'The field :{field} must be a string consisting of alphabets, numbers & special characters.';
        }

        $valueHasAtLeastOneSymbol = false;

        foreach ($this->symbols as $symbol) {
            if (str_contains_v2($value, $symbol)) {
                $valueHasAtLeastOneSymbol = true;
                break;
            }
        }

        if (!$valueHasAtLeastOneSymbol) {
            return 'The field :{field} must be a string consisting of alphabets, numbers & special characters.';
        }

        return true;
    }
}
