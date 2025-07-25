<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeBoolean extends AbstractRule
{
    /**
     * All of the valid boolean values.
     * 
     * @var array<int, bool|int|string> $validBooleans
     */
    protected array $validBooleans = [true, false, 0, 1, 'true', 'false', '1', '0', 'on', 'off'];

    /**
     * It includes only those parameters that are expected for the value.
     *
     * @var array<int, bool|int|string> $expectedBooleans
     */
    protected array $expectedBooleans = [];

    /**
     * @param   bool|int|string ...$expectedBooleans
     * 
     * @throws  \Exception
     */
    public function __construct(bool|int|string ...$expectedBooleans)
    {
        if (empty($expectedBooleans)) {
            $this->expectedBooleans = $this->validBooleans;
            return $this;
        }

        $invalidValues = array_diff($this->validBooleans, $expectedBooleans);

        if (!empty($invalidValues)) {
            throw new Exception("[Developer][Exception]: The validation rule [boolean] accepts only these arguments: true (bool), false (bool), true (string), false (string), 1 (int), 0 (int), 1 (string), 0 (string), on (string), off (string).");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $transformedValue = is_string($value) ? strtolower($value) : $value;

        if (!in_array($transformedValue, $this->expectedBooleans, true)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return "The field :{field} must be one of these boolean values: " . \implode(', ', $this->expectedBooleans);
    }
}
