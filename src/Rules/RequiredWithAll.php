<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRuleInterface;

/**
 * @version 1.0
 */
class RequiredWithAll extends AbstractRule implements MandatoryRuleInterface
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @var array $otherFields
     */
    protected array $otherFields;

    /**
     * @param string $otherFields
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
        $allOtherFieldsArePresent = true;

        // If any of the dependent fields is not filled OR not equal to null, the validation
        // will return true. However, if the all the dependent fields are present & if the
        // current field is missing, a validation error message will be returned.
        foreach ($this->otherFields as $otherField) {
            if ($this->input->isNull($otherField)) {
                $allOtherFieldsArePresent = false;
            }
        }

        if ($allOtherFieldsArePresent && \is_null($value)) {
            $this->message = "The field {$field} is required when these fields are present: " . \implode(', ', $this->otherFields);

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
