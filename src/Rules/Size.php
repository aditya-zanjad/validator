<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;
use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class Size extends AbstractRule
{
    /**
     * @var $validSize
     */
    protected $validSize;

    /**
     * @param mixed $size
     */
    public function __construct($size)
    {
        $this->validSize = varEvaluateType($size);
    }

    /**
     * Check if the given value matches the given size or not.
     *
     * @param   string  $field
     * @param   mixed   $value
     */
    public function check(string $field, mixed $value): bool|string
    {
        $size               =   varSize($value);
        $valueSizeIsValid   =   $size === $this->validSize;

        if ($valueSizeIsValid) {
            return true;
        }

        // Depending on the data type of the current value, we'll dynamically prepare the error message.
        switch (gettype($value)) {
            case 'array':
                return "The array {$field} must contain exactly {$this->validSize} elements.";

            case 'string':
                return "The string {$field} must contain exactly {$this->validSize} elements.";

            case 'resource':
                return "The resource {$field} must be of the length {$this->validSize} bytes.";

            case 'float':
            case 'double':
                return "The field {$field} must be equal to the float value {$this->validSize}.";

            case 'integer':
                return "The field {$field} must be equal to the integer value {$this->validSize}.";

            default:
                return "The size of the field {$field} must be equal to {$this->validSize}.";
        }
    }
}
