<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;
use function AdityaZanjad\Validator\Utils\varMakeSize;

/**
 * @version 1.0
 */
class LessThan extends AbstractRule
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
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenSize)
    {
        $this->givenSize    =   $givenSize;
        $givenSize          =   varMakeSize($givenSize);

        if (\is_null($givenSize)) {
            throw new Exception("[Developer][Exception]: The validation rule [size] accepts only one parameter which should be either an [INTEGER], [FLOAT] or a [STRING].");
        }

        $this->maxThreshold = $givenSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return varSize($value) < $this->maxThreshold;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be less than {$this->givenSize}";
    }
}
