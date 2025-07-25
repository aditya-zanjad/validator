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
     * @var string $message
     */
    protected string $message;

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
    public function check(string $field, $value): bool
    {
        $currentFieldIsPresent = !\is_null($value);

        // The current field is required iff any of the dependent fields is not present in the input.
        foreach ($this->otherFields as $otherField) {
            if ($this->input->notNull($otherField) && $currentFieldIsPresent) {
                $this->message = "The field {$field} is required without the field {$otherField}.";
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
