<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\str_contains_v2;

/**
 * @version 1.0
 */
class Alpha extends AbstractRule
{
    /**
     * The characters that should not be allowed in the string.
     *
     * @var array<int, string> $illegalCharacters
     */
    protected array $illegalCharacters = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '-', '_', '=', '+', '\\', '|', ';', ':', '\'', '"', ',', '<', '.', '>', '/', '?', ' '];

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be an alphabet-only string.';
        }

        if (function_exists('preg_match') && preg_match('/^[a-zA-Z]+$/', $value) === false) {
            return 'The field :{field} must be an alphabet-only string.';
        }

        foreach ($this->illegalCharacters as $character) {
            if (str_contains_v2($value, $character)) {
                return 'The field :{field} must be an alphabet-only string.';
            }
        }

        return true;
    }
}
