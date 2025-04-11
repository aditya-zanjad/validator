<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Traits\VarHelpers;

/**
 * @version 1.0
 */
class Equal extends AbstractRule
{
    use VarHelpers;

    /**
     * @var mixed $data
     */
    protected mixed $data;

    /**
     *
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        $this->data = is_string($data) ? $this->varEvaluateType($data) : $data;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if ($value !== $this->data) {
            return "The field {$field} must be equal to {$this->data}.";
        }

        return true;
    }
}
