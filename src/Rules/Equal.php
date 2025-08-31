<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * 
 * @version 1.0
 */
class Equal extends AbstractRule
{
    /**
     * @var bool|int|float|string $validValue
     */
    protected bool|int|float|string $validValue;

    /**
     * @param bool|int|float|string $givenValue
     */
    public function __construct(bool|int|float|string $givenValue)
    {
        $this->validValue = varEvaluateType($givenValue);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        return varEvaluateType($value) === $this->validValue;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be equal to {$this->validValue}.";
    }
}
