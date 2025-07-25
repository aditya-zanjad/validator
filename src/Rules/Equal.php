<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Equal extends AbstractRule
{
    /**
     * @var string $comparingData
     */
    protected string $comparingData;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->comparingData = $data;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return $value == $this->comparingData;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be equal to {$this->comparingData}.";
    }
}
