<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class RequiredUnless extends AbstractRule implements RequisiteRule
{
    /**
     * @var string $anotherField
     */
    protected string $anotherField;

    /**
     * @var mixed $anotherFieldValidValues
     */
    protected mixed $anotherFieldValidValues;

    /**
     * @param   string  $anotherField
     * @param   string  ...$anotherFieldValidValues
     */
    public function __construct(string $anotherField, string ...$anotherFieldValidValues)
    {
        $this->anotherField             =   $anotherField;
        $this->anotherFieldValidValues  =   array_map(fn ($value) => varEvaluateType($value), array_values($anotherFieldValidValues));
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $anotherFieldValue          =   $this->input->get($this->anotherField);
        $currentFieldIsPresent      =   !is_null($value);
        $anotherFieldHasValidValue  =   in_array($anotherFieldValue, $this->anotherFieldValidValues);

        // If another field does not match with any of the values given for it & the current field is present.
        if (!$anotherFieldHasValidValue && $currentFieldIsPresent) {
            return true;
        }

        // If another field is equal to any of the given values & the current field is not present OR is NULL.
        if ($anotherFieldHasValidValue && !$currentFieldIsPresent) {
            return true;
        }

        $this->anotherFieldValidValues = array_map(fn ($value) => !is_null($value) ? $value : 'null', $this->anotherFieldValidValues);

        if (count($this->anotherFieldValidValues) === 1) {
            return "The field {$field} is required only if the field {$this->anotherField} is not equal to {$this->anotherFieldValidValues[0]}.";
        }

        $implodedValidValues = implode(', ', $this->anotherFieldValidValues);
        return "The field {$field} is required only if the field {$this->anotherField} is not equal to any of these values: {$implodedValidValues}.";
    }
}
