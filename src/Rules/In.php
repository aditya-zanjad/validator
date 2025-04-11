<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Core\Utils\Arr;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\VarHelpers;

/**
 * @version 1.0
 */
class In extends AbstractRule
{
    use VarHelpers;

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
        $this->params = Arr::mapFn($params, fn ($param) => is_string($param) ? trim($param) : $param);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (!in_array($value, $this->params)) {
            $validValues = implode(', ', $this->params);
            return "The field {$field} must be one of these values: {$validValues}.";
        }

        return true;
    }
}
