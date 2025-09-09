<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;
use function AdityaZanjad\Validator\Utils\varFilterSize;

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
     * @param int|float|string $givenSize
     */
    public function __construct(int|float|string $givenSize)
    {
        $this->givenSize    =   $givenSize;
        $this->validSize    =   varFilterSize($givenSize);
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $size = varSize($value);

        if ($size != $this->validSize) {
            $this->message = match (\gettype($value)) {
                'array'                         =>  "The array :{field} must contain exactly {$this->validSize} elements.",
                'string'                        =>  "The field :{field} must contain exactly {$this->validSize} characters.",
                'resource'                      =>  "The file :{field} must be {$this->validSize} in size.",
                'float', 'double', 'integer'    =>  "The field :{field} must be equal to {$this->validSize}.",
                default                         =>  "The field :{field} must be of the size {$this->validSize}.",
            };

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
}
