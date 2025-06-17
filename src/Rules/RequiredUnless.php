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
     * @var string $otherField
     */
    protected string $otherField;

    /**
     * @var mixed $otherFieldValidValues
     */
    protected mixed $otherFieldValidValues;

    /**
     * @param   string  $otherField
     * @param   string  ...$otherFieldValidValues
     */
    public function __construct(string $otherField, string ...$otherFieldValidValues)
    {
        $this->otherField = $otherField;

        // Initially, the given values will be in a stringified format. For example, '1', 'true' etc.
        // We want to convert them to their actual data type for comparison in the below method.
        $this->otherFieldValidValues = array_map(function ($value) {
            return varEvaluateType($value);
        }, array_values($otherFieldValidValues));
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $otherFieldValue            =   $this->input->get($this->otherField);
        $currentFieldIsPresent      =   !is_null($value);
        $otherFieldHasValidValue    =   in_array($otherFieldValue, $this->otherFieldValidValues);

        // If another field does not match with any of the values given for it & the current field is present.
        if (!$otherFieldHasValidValue && $currentFieldIsPresent) {
            return true;
        }

        if ($otherFieldHasValidValue && !$currentFieldIsPresent) {
            return true;
        }

        $stringifiedOtherFieldValidValues = array_map(function ($value) {
            return !is_null($value) ? $value : '[NULL]';
        }, $this->otherFieldValidValues);

        if (count($stringifiedOtherFieldValidValues) === 1) {
            return "The field {$field} is required if the field {$this->otherField} is not equal to {$stringifiedOtherFieldValidValues[0]}.";
        }

        $implodedValidValues = implode(', ', $stringifiedOtherFieldValidValues);
        return "The field {$field} is required if the field {$this->otherField} is not equal to any of these values: {$implodedValidValues}.";
    }
}
