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
        $currentFieldIsMissing = \is_null($value);

        if (!$currentFieldIsMissing) {
            return true;
        }

        // The current field is required iff any of the dependent fields is not present in the input.
        foreach ($this->otherFields as $otherField) {
            if ($this->input->isNull($otherField) && !$currentFieldIsMissing) {
                return "The field {$field} is required without the field {$otherField}.";
            }
        }

        return true;
    }
}
