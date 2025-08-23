<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use Exception;

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
    public function __construct(string $size)
    {
        $this->validSize = $this->transformGivenSize($size);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $size = varSize($value);
        $valueSizeIsValid   =   $size === $this->validSize || $size == $this->validSize;

        if ($valueSizeIsValid) {
            return true;
        }

        // Depending on the data type of the current value, we'll dynamically prepare the error message.
        $this->message = match (gettype($value)) {
            'array'             =>  "The array {$field} must contain exactly {$this->validSize} elements.",
            'string'            =>  "The string {$field} must contain exactly {$this->validSize} elements.",
            'resource'          =>  "The resource {$field} must be of the length {$this->validSize} bytes.",
            'float', 'double'   =>  "The field {$field} must be equal to the float value {$this->validSize}.",
            'integer'           =>  "The field {$field} must be equal to the integer value {$this->validSize}.",
            default             =>  "The size of the field {$field} must be equal to {$this->validSize}.",
        };

        return false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }

    protected function transformFileSize(string $size)
    {
        $size           =   \str_replace(' ', '', $size);
        $lastTwoChars   =   \substr($size, -1, 2);

        return match ($lastTwoChars) {
            'B', 'bytes'    =>  $size,
            'KB'            =>  $size * 1024,
            'MB'            =>  $size * 1024 * 1024,
            'GB'            =>  $size * 1024 * 1024 * 1024,
            default         =>  throw new Exception("[Developer][Exception]: The given file size unit [{$lastTwoChars}] is invalid.")
        };
    }
}
