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
    protected array $params = [];

    /**
     * Inject necessary dependencies in the class.
     *
     * @param bool|int|float|string ...$params
     */
    public function __construct(bool|int|float|string ...$params)
    {
        foreach ($params as $param) {
            if (\is_bool($param)) {
                $param = $param ? 'true' : 'false';
            }

            $this->params[] = \trim((string) $param);
        }
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        return \in_array($value, $this->params);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        $joinedValues = \array_reduce($this->params, fn ($carry, $value) => "{$carry}\"{$value}\", ");
        $joinedValues = \rtrim($joinedValues, ', ');
        
        return "The field :{field} must be set to one of these values: {$joinedValues}";
    }
}
