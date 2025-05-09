<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Size extends AbstractRule
{
    /**
     * @var string $validSize
     */
    protected string $validSize;

    /**
     * @param string $size
     */
    public function __construct(string $size)
    {
        $this->validSize = $size;
    }

    /**
     * Check if the given value matches the given size or not.
     *
     * @param   string  $field
     * @param   mixed   $value
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (varSize($value) != $this->validSize) {
            return "The size of the field {$field} must be equal to {$this->validSize}.";
        }

        return true;
    }
}
