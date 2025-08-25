<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

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
     * The user-supplied size for validating the size of the input value.
     *
     * @var int|float|string
     */
    protected int|float|string $givenSize;

    /**
     * @var int|float $validSize
     */
    protected int|float $validSize;

    /**
     * The actual processed value from the user-supplied value that'll be utilized for the validation of the given value.
     *
     * @var int|float $validSize
     */
    public function __construct(int|float|string $givenSize)
    {
        $this->givenSize    =   $givenSize;
        $this->validSize    =   $this->transformGivenSize($givenSize);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $size = varSize($value);

        if ($size == $this->validSize) {
            return true;
        }

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

    protected function transformGivenSize(mixed $value): int|float
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return (float) $value;
        }

        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return (int) $value;
        }

        $size           =   \str_replace(' ', '', $value);
        $lastTwoChars   =   \substr($size, -2);
        $lastTwoChars   =   \strtolower($lastTwoChars);

        return match ($lastTwoChars) {
            'b'     =>  (float) $size,
            'kb'    =>  (float) $size * 1024,
            'mb'    =>  (float) $size * 1024 * 1024,
            'gb'    =>  (float) $size * 1024 * 1024 * 1024,
            default =>  throw new Exception("[Developer][Exception]: The given file size unit [{$lastTwoChars}] is invalid.")
        };
    }
}
