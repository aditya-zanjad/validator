<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use AdityaZanjad\Validator\Input;

/**
 * @version 1.0
 */
abstract class AbstractRule
{
    /**
     * An instance that'll allow access to all of the input data.
     *
     * @var \AdityaZanjad\Validator\Input $input
     */
    protected Input $input;

    /**
     * Set an instance that'll make all of the input data accessible in the current class.
     *
     * @param \AdityaZanjad\Validator\Input $input
     *
     * @return static
     */
    final public function setInput(Input $input): static
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Perform the validation logic on the given input field value.
     *
     * @param   string  $field  =>  A dot notation path to the field inside the input data.
     * @param   mixed   $value  =>  Value of the input field.
     *
     * @return  bool|string
     */
    abstract public function check(string $field, mixed $value);
}
