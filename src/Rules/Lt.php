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
class Lt extends AbstractRule
{
    /**
     * @var int|float|string $givenSize
     */
    protected $givenSize;

    /**
     * @var int|float $maxThreshold
     */
    protected int|float $maxThreshold;

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
        $this->givenSize    =   $givenSize;
        $givenSize          =   varFilterSize($givenSize);

        if (\is_null($givenSize)) {
            throw new Exception("[Developer][Exception]: The validation rule [size] accepts only one parameter which should be either an [INTEGER], [FLOAT] or a [STRING].");
        }

        $this->maxThreshold = $givenSize;
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        if (varSize($value) >= $this->maxThreshold) {
            $this->message = match (\gettype($value)) {
                'array'                                     =>  "The field :{field} must contain less than {$this->maxThreshold} elements.",
                'string'                                    =>  "The field :{field} must contain less than {$this->maxThreshold} characters.",
                'resource', 'float', 'double', 'integer'    =>  "The field :{field} must be less than {$this->maxThreshold}.",
                default                                     =>  "The field :{field} must be less than {$this->maxThreshold}."
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
