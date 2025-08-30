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
class Gt extends AbstractRule
{
    /**
     * @var int|float|string $givenSize
     */
    protected $givenSize;

    /**
     * @var int|float $minThreshold
     */
    protected int|float $minThreshold;

    /**
     * @var string $message
     */
    protected string $message;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenSize)
    {
        $transformedSize = varFilterSize($givenSize);

        if (\is_null($transformedSize)) {
            throw new Exception("[Developer][Exception]: The validation rule [gte] accepts only one parameter which should be either an [INTEGER], [FLOAT] or a [STRING].");
        }

        $this->givenSize    =   $givenSize;
        $this->minThreshold =   $transformedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        if (varSize($value) <= $this->minThreshold) {
            $this->message = match (\gettype($value)) {
                'array'                                     =>  "The field {$field} must contain more than {$this->minThreshold} elements.",
                'string'                                    =>  "The field {$field} must contain more than {$this->minThreshold} characters.",
                'resource', 'float', 'double', 'integer'    =>  "The field {$field} must be more than {$this->minThreshold}.",
                default                                     =>  "The field {$field} must be more than {$this->minThreshold}."
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
