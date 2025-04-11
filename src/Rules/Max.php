<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Traits\VarHelpers;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Max extends AbstractRule
{
    use VarHelpers;

    /**
     * @var int $maxAllowedSize
     */
    protected int $maxAllowedSize;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param int $maxAllowedSize
     */
    public function __construct(int|string $maxAllowedSize)
    {
        $this->maxAllowedSize = (int) $maxAllowedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if ($this->varSize($value) > $this->maxAllowedSize) {
            return "The field {$field} cannot be more than {$this->maxAllowedSize}.";
        }

        return true;
    }
}
