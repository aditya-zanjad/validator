<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRuleInterface;

use function AdityaZanjad\Validator\Utils\varIsEmpty;
use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class RequiredUnless extends AbstractRule implements MandatoryRuleInterface
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @var string $otherField
     */
    protected string $otherField;

    /**
     * @var array<int, mixed> $otherFieldAllowedValues
     */
    protected array $otherFieldAllowedValues;

    /**
     * @param   string  $otherField
     * @param   string  ...$otherFieldAllowedValues
     */
    public function __construct(string $otherField, string ...$otherFieldAllowedValues)
    {
        $this->otherField = $otherField;

        /**
         * Evaluate the given values for the other field to their respective data types.
         * 
         * Examples: 
         * 'null' gets evaluated NULL, 
         * '123' gets evaluated to integer 123 & so on.
         */
        $this->otherFieldAllowedValues = \array_map(fn ($value) => varEvaluateType($value), \array_values($otherFieldAllowedValues));
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $otherFieldValue            =   $this->input->get($this->otherField);
        $currentFieldIsPresent      =   !varIsEmpty($value);
        $otherFieldHasExpectedValue =   \in_array($otherFieldValue, $this->otherFieldAllowedValues);

        /**
         * If the other field does not equal to any of the expected values & the current is present [i.e. not missing or not NULL]
         * Other field equals one of the expected values & the current field is missing [i.e. is missing or is NULL] 
         */
        if ((!$otherFieldHasExpectedValue && $currentFieldIsPresent) || $otherFieldHasExpectedValue && !$currentFieldIsPresent) {
            return true;
        }

        // If any of the expected values is/are NULL, we want to convert them to a string 'NULL' in order to represent them in the error message.
        $otherFieldAllowedValues    =   \array_map(fn ($value) => !varIsEmpty($value) ? $value : '[NULL]', $this->otherFieldAllowedValues);
        $otherFieldAllowedValues    =   \implode(', ', $otherFieldAllowedValues);

        $this->message = "The field :{field} is required if the field {$this->otherField} is not equal to any of these values: {$otherFieldAllowedValues}";
        return false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
