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
class RequiredIf extends AbstractRule implements MandatoryRuleInterface
{
    /**
     * @var string $message
     */
    protected string $message;

    /**
     * The other field on which the validation logic of the current rule depends.
     *
     * @var string $otherField
     */
    protected string $otherField;

    /**
     * The values of the other field that'll be used when applying the validation logic.
     *
     * @var array<int, mixed> $otherFieldValues
     */
    protected array $otherFieldValues;

    /**
     * @param   string  $otherField
     * @param   string  ...$otherFieldValues
     */
    public function __construct(string $otherField, mixed ...$otherFieldValues)
    {
        $this->otherField       =   $otherField;
        $this->otherFieldValues =   $otherFieldValues;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        $otherFieldValue = $this->input->get($this->otherField);

        if (varIsEmpty($otherFieldValue)) {
            return true;
        }

        if (!\in_array($otherFieldValue, \array_map(fn ($val) => varEvaluateType($val), $this->otherFieldValues), true)) {
            return true;
        }

        if (varIsEmpty($value)) {
            $this->message = "The field {$field} is required when the field {$this->otherField} is equals to: {$otherFieldValue}.";
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
