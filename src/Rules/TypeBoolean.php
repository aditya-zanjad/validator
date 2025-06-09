<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeBoolean extends AbstractRule
{
    /**
     * @var array<int, bool|int|string> $validBooleans
     */
    protected array $validBooleans = [true, false, 'true', 'false', 0, 1, '1', '0'];

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!in_array($value, $this->validBooleans, true)) {
            return "The field {$field} must be a boolean value.";
        }

        return true;
    }
}
