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
    protected array $validBooleans = [true, false, 'true', 'false', 0, 1, '1', '0', 'on', 'off'];

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $transformedValue = $this->transformValue($value);

        if (!in_array($transformedValue, $this->validBooleans, true)) {
            return "The field {$field} must be a boolean value.";
        }

        return true;
    }

    /**
     * Transform the given value based on certain conditions.
     *
     * @param bool|int|string $value
     * 
     * @return bool|int|string
     */
    protected function transformValue($value)
    {
        if (is_string($value)) {
            return strtolower($value);
        }

        return $value;
    }
}
