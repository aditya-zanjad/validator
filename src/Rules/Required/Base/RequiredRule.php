<?php

namespace AdityaZanjad\Validator\Rules\Required\Base;

use AdityaZanjad\Validator\Interfaces\ValidationRule;

abstract class RequiredRule implements ValidationRule
{
    /**
     * Necessary data required to perform validation.
     *
     * The 'array keys' are the dot notation path towards their respective field values.
     *
     * @var array<string, mixed> $data
     */
    protected readonly array $data;

    /**
     * Set the necessary data required for validation.
     *
     * @param array<string, mixed> $data
     *
     * @return static
     */
    final public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }
}
