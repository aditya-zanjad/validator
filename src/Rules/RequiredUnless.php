<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class RequiredUnless extends AbstractRule implements RequisiteRule
{
    /**
     * @var string $dependentField
     */
    protected string $dependentField;

    /**
     * @var mixed $validDependentValues
     */
    protected mixed $validDependentValues;

    /**
     * @param   string  $dependentField
     * @param   mixed   $validDependentValues
     */
    public function __construct(string $dependentField, mixed $validDependentValues)
    {
        $this->dependentField       =   $dependentField;
        $this->validDependentValues =   $validDependentValues;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $currentFieldIsPresent  =   $this->input->exists($field);
        $dependentFieldValue    =   $this->input->get($this->dependentField);

        /**
         * If the dependent field's value does not equal to any of its provided
         * value and the current field is missing as well, then the
         * validation fails.
         */
        foreach ($this->validDependentValues as $validValue) {
            if ($dependentFieldValue == $validValue && $currentFieldIsPresent) {
                return "The field {$field} is required only if the field {$this->dependentField} is not equal to: {$validValue}.";
            }
        }

        return true;
    }
}
