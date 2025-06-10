<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class RequiredWithAll extends AbstractRule implements RequisiteRule
{
    /**
     * @var array $dependentFields
     */
    protected array $dependentFields;

    /**
     * @param string $dependentFields
     */
    public function __construct(string ...$dependentFields)
    {
        $this->dependentFields = $dependentFields;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $allDependentsArePresent = true;

        // If any of the dependent fields is not filled OR not equal to null, the validation
        // will return true. However, if the all the dependent fields are present & if the
        // current field is missing, a validation error message will be returned.
        foreach ($this->dependentFields as $dependentField) {
            if ($this->input->isNull($dependentField)) {
                $allDependentsArePresent = false;
            }
        }

        if ($allDependentsArePresent && is_null($value)) {
            $implodededDependentFields = implode(', ', $this->dependentFields);
            return "The field {$field} is required when these fields are present: {$implodededDependentFields}.";
        }

        return true;
    }
}
