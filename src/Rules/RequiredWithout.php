<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class RequiredWithout extends AbstractRule implements RequisiteRule
{
    /**
     * The dependent fields against which we want to check the existence of the current field.
     *
     * @var array<int, string> $dependentFieldValues
     */
    protected array $dependentFieldValues;

    /**
     * Inject necessary dependencies into the class.
     *
     * @param string ...$dependentFieldValues
     */
    public function __construct(string ...$dependentFieldValues)
    {
        $this->dependentFieldValues = $dependentFieldValues;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $currentFieldExists = $this->input->exists($field);

        // The current field is required only iff any of the dependent fields is not present in the input.
        foreach ($this->dependentFieldValues as $dependentFieldValue) {
            if (!$this->input->exists($dependentFieldValue) && !$currentFieldExists) {
                return "The field {$field} is required when the field {$dependentFieldValue} is missing.";
            }
        }

        return true;
    }
}
