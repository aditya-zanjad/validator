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
     * Get the validation error message.
     * 
     * @return string
     */
    public function message(): string
    {
        return 'The field :{field} is invalid.';
    }

    /**
     * Perform the validation logic on the given input field value.
     *
     * @param   string  $field  =>  A dot notation path to the field inside the input data.
     * @param   mixed   $value  =>  Value of the input field.
     *
     * @return  bool
     */
    abstract public function check(string $field, mixed $value): bool;
}
