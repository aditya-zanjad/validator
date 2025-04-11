<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class RequiredWith extends AbstractRule implements RequisiteRule
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
        $currentFieldIsMissing = !$this->input->exists($field);

        // If any of the dependent field is not filled i.e. not equal to null, the validation
        // will return true. However, if the all the dependent fields are present & if the
        // current field is missing, a validation error message will be returned.
        foreach ($this->dependentFields as $dependentField) {
            if ($this->input->exists($dependentField) && $currentFieldIsMissing) {
                return "The field {$field} is required along with the field {$dependentField}.";
            }
        }

        return true;
    }
}
