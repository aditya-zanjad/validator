<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\str_contains_v2;

/**
 * @version 1.0
 */
class AlphaNum extends AbstractRule
{
    /**
     * The characters that should not be allowed in the string.
     *
     * @var array<int, string> $illegalCharacters
     */
    protected array $illegalCharacters = ['`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '_', '=', '+', '\\', '|', ';', ':', '\'', '"', ',',  '<', '.', '>', '/', '?', ' '];

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be an alpha-numeric string.';
        }

        if (function_exists('preg_match') && preg_match('/^[a-zA-Z0-9]+$/', $value) === false) {
            return 'The field :{field} must be an alpha-numeric string.';
        }

        foreach ($this->illegalCharacters as $character) {
            if (str_contains_v2($value, $character)) {
                return 'The field :{field} must be an alpha-numeric string.';
            }
        }

        return true;
    }
}
