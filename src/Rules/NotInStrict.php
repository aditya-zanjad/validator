<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class NotInStrict extends AbstractRule
{
    /**
     * Valid values for the given input field.
     *
     * @var array<int, string> mixed
     */
    protected array $params;

    /**
     * Inject necessary dependencies in the class.
     *
     * @param string ...$params
     */
    public function __construct(...$params)
    {
        $this->params = array_map(fn($param) => is_string($param) ? trim($param) : $param, $params);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (in_array($value, $this->params, true)) {
            $validValues = implode(', ', $this->params);
            return "The field :{field} must not be any of these values: {$validValues}.";
        }

        return true;
    }
}
