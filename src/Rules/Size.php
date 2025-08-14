<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;
use function AdityaZanjad\Validator\Utils\varMakeSize;
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
     * The user-supplied size for validating the size of the input value.
     *
     * @var int|float|string
     */
    protected int|float|string $givenSize;

    /**
     * The actual processed value from the user-supplied value that'll be utilized for the validation of the given value.
     *
     * @var int|float $validSize
     */
    protected int|float $validSize;

    /**
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenSize)
    {
        $evaluatedSize = varEvaluateType($givenSize);
        $evaluatedSize = varMakeSize($givenSize);

        if (\is_null($evaluatedSize)) {
            throw new Exception("[Developer][Exception]: The validation rule [size] accepts only one parameter & it should be either an [INTEGER], [FLOAT] or a [STRING]. Make sure that you've provided the correct parameter to this validation rule.");
        }

        $this->givenSize = $givenSize;
        $this->validSize = $evaluatedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $size = varSize($value);

        if ($size !== $this->validSize && $size != $this->validSize) {
            $this->message = $this->makeErrorMessage($field, $value);
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * Make the error message based on the data type of the given value.
     *
     * @param   string  $field
     * @param   mixed   $value
     *
     * @return  string
     */
    protected function makeErrorMessage(string $field, mixed $value): string
    {
        return match (gettype($value)) {
            'array'                         =>  "The array {$field} must contain exactly {$this->givenSize} elements.",
            'string'                        =>  \is_file($value) ? "The file {$field} must have the exact size: {$this->givenSize}." : "The string {$field} must have the exact size: {$this->givenSize}.",
            'integer', 'float', 'double'    =>  "The numeric {$field} must be exactly equal to the size: {$this->givenSize}.",
            default                         =>  "The field {$field} must have the exact size: {$this->givenSize}"
        };
    }
}
