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
     * @var string $message
     */
    protected string $message;  

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
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $size               =   varSize($value);
        $valueSizeIsValid   =   $size === $this->validSize || $size == $this->validSize;

        if ($valueSizeIsValid) {
            return true;
        }

        // Depending on the data type of the current value, we'll dynamically prepare the error message.
        switch (gettype($value)) {
            case 'array':
                $this->message = "The array {$field} must contain exactly {$this->validSize} elements.";

            case 'string':
                $this->message = "The string {$field} must contain exactly {$this->validSize} elements.";

            case 'resource':
                $this->message = "The resource {$field} must be of the length {$this->validSize} bytes.";

            case 'float':
            case 'double':
                $this->message = "The field {$field} must be equal to the float value {$this->validSize}.";

            case 'integer':
                $this->message = "The field {$field} must be equal to the integer value {$this->validSize}.";

            default:
                $this->message = "The size of the field {$field} must be equal to {$this->validSize}.";
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
