<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class InStrict extends AbstractRule
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
        $this->params = array_map( function ($param) {
            $param = varEvaluateType($param);
            return is_string($param) ? trim($param) : $param;
        }, $params);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $value = varEvaluateType($value);

        if (!in_array($value, $this->params, true)) {
            $validValues = implode(', ', $this->params);
            return "The field {$field} must be one of these values: {$validValues}.";
        }

        return true;
    }
}
