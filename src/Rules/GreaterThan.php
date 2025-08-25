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
class GreaterThan extends AbstractRule
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
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenSize)
    {
        $transformedSize = varMakeSize($givenSize);

        if (\is_null($transformedSize)) {
            throw new Exception("[Developer][Exception]: The validation rule [gte] accepts only one parameter which should be either an [INTEGER], [FLOAT] or a [STRING].");
        }

        $this->givenSize    =   $givenSize;
        $this->minThreshold =   $transformedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return varSize($value) > $this->minThreshold;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be greater than {$this->givenSize}";
    }
}
