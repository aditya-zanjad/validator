<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Min extends AbstractRule
{
    /**
     * @var int $minAllowedSize
     */
    protected int $minAllowedSize;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param int $minAllowedSize
     */
    public function __construct(int|string $minAllowedSize)
    {
        $this->minAllowedSize = (int) $minAllowedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (varSize($value) < $this->minAllowedSize) {
            return "The field {$field} cannot be less than {$this->minAllowedSize}.";
        }

        return true;
    }
}
