<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

/**
 * @version 1.0
 */
class RequiredWithoutAll extends AbstractRule implements RequisiteRule
{
    /**
     * The dependent fields against which we want to check the existence of the current field.
     *
     * @var array<int, string> $otherFields
     */
    protected array $otherFields;

    /**
     * Inject necessary dependencies into the class.
     *
     * @param string ...$otherFields
     */
    public function __construct(string ...$otherFields)
    {
        $this->otherFields = $otherFields;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $currentFieldIsPresent = !\is_null($value);

        $otherFieldsPresences = array_map(function ($field) {
            return $this->input->notNull($field);
        }, $this->otherFields);

        $allOtherFieldsArePresent = (bool) \array_product($otherFieldsPresences);

        // The input field should be present only if other fields are missing and vice versa.
        if (!$currentFieldIsPresent && !$allOtherFieldsArePresent) {
            $implodedOtherFields = \implode(', ', $this->otherFields);
            return "The field {$field} is required when all these other fields are missing: {$implodedOtherFields}.";
        }

        return true;
    }
}
