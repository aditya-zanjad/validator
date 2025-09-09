<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Same extends AbstractRule
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @var string $comparingField
     */
    protected string $comparingField;

    /**
     * @param string $comparingField
     */
    public function __construct(string $comparingField)
    {
        $this->comparingField = $comparingField;
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        if ($value !== $this->input->get($this->comparingField)) {
            $this->message = "The value of the field :{field} must be the same as that of the field {$this->comparingField}.";
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
