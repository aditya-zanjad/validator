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
class Between extends AbstractRule
{
    /**
     * @var int|float|string $givenMinSize
     */
    protected $givenMinSize;

    /**
     * @var int|float|string $givenMaxSize
     */
    protected $givenMaxSize;

    /**
     * @var int|float $minSize
     */
    protected int|float $minSize;

    /**
     * @var int|float $maxSize
     */
    protected int|float $maxSize;

    /**
     * @var string $message
     */
    protected string $message;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenMinSize, mixed $givenMaxSize)
    {
        $this->givenMinSize =   $givenMinSize;
        $this->minSize      =   varFilterSize($givenMinSize);

        if (\is_null($this->minSize)) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [between] must be either [INTEGER] or [FLOAT].");
        }

        $this->givenMaxSize =   $givenMaxSize;
        $this->maxSize      =   varFilterSize($givenMaxSize);

        if (\is_null($this->minSize)) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [between] must be either [INTEGER] or [FLOAT].");
        }

        if ($this->maxSize < $this->minSize) {
            throw new Exception("[Developer][Exception]: The validation rule [between] requires that the max size parameter be greater than or equal to the min size parameter.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $size = varSize($value); 

        if ($size < $this->minSize || $size > $this->maxSize) {
            $this->message = match (\gettype($value)) {
                'array'                                     =>  "The field {$field} must contain elemeents between the range {$this->minSize} and {$this->maxSize}.",
                'string'                                    =>  "The field {$field} must contain characters between the range {$this->minSize} and {$this->maxSize}.",
                'resource', 'float', 'double', 'integer'    =>  "The field {$field} must be between the range {$this->minSize} and {$this->maxSize}.",
                default                                     =>  "The field {$field} must be between the range {$this->minSize} and {$this->maxSize}."
            };

            return false;
        };

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
