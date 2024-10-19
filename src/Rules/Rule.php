<?php

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Input;
use AdityaZanjad\Validator\Interfaces\ValidationRule;

abstract class Rule implements ValidationRule
{
    /**
     * An instance to help manage the provided input data.
     *
     * @var \AdityaZanjad\Validator\Input $input
     */
    protected readonly Input $input;

    /**
     * @param \AdityaZanjad\Validator\Input $input
     *
     * @return static
     */
    final public function setInputInstance(Input $input): static
    {
        $this->input = $input;
        return $this;
    }
}
