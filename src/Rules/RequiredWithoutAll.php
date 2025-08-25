<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRuleInterface;

/**
 * @version 1.0
 */
class RequiredWithoutAll extends AbstractRule implements MandatoryRuleInterface
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

        $allOtherFieldsArePresent = array_reduce($this->otherFields, function ($carry, $field) {
            return $carry && $this->input->notNull($field);
        }, true);

        if ($allOtherFieldsArePresent && $currentFieldIsPresent) {
            $this->message = "The field {$field} is required without all these fields: " . \implode(', ', $this->otherFields);
            return false;
        }

        if (!$allOtherFieldsArePresent && !$currentFieldIsPresent) {
            $this->message = "The field {$field} is required when these fields are missing: " . \implode(', ', $this->otherFields);
            return false;
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
