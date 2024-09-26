<?php

namespace AdityaZanjad\Validator\Interfaces;

interface ConstraintRule extends ValidationRule
{
    /**
     * Set the data that is dependent/constrained to other data.
     *
     * @param array<string, mixed> $data
     *
     * @return static
     */
    public function setConstraintData(array $data): static;
}
