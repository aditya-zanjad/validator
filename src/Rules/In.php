<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class In extends AbstractRule
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
    public function __construct(string ...$params)
    {
        $this->params = array_map(fn ($param) => \trim($param), $params);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return in_array($value, $this->params);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be set to one of these values: " . implode(', ', $this->params);
    }
}
