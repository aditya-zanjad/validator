<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use AdityaZanjad\Validator\Interfaces\InputManagerInterface;

/**
 * @version 1.0
 */
abstract class AbstractRule
{
    /**
     * An instance that'll allow access to all of the input data.
     *
     * @var \AdityaZanjad\Validator\Interfaces\InputManagerInterface $input
     */
    protected InputManagerInterface $input;

    /**
     * The name of the input field being validated.
     *
     * @var string $field
     */
    protected string $field;

    /**
     * Set an instance that'll make all of the input data accessible in the current class.
     *
     * @param \AdityaZanjad\Validator\Interfaces\InputManagerInterface $input
     *
     * @return static
     */
    public function setInput(InputManagerInterface $input): static
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Set the name of the input field being validated.
     * 
     * @param string $field
     *
     * @return \AdityaZanjad\Validator\Base\AbstractRule
     */
    public function setField(string $field): AbstractRule
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Get the validation error message.
     * 
     * @return string
     */
    public function message(): string
    {
        return 'The field :{field} is invalid.';
    }

    /**
     * Validate the given data.
     * 
     * @param mixed $value
     * 
     * @return bool
     */
    abstract public function check(mixed $value): bool;
}
