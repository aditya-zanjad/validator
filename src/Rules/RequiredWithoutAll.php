<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Core\Utils\Arr;
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
     * @var array<int, string> $dependentFields
     */
    protected array $dependentFields;

    /**
     * Inject necessary dependencies into the class.
     *
     * @param string ...$dependentFields
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
        $currentFieldExists     =   $this->input->exists($field);
        $dependentFieldsExist   =   Arr::mapFn($this->dependentFields, fn ($field) => $this->input->exists($field));
        $dependentFieldExists   =   (bool) array_product($dependentFieldsExist);

        // The input field should be present only if
        if ($currentFieldExists && $dependentFieldExists) {
            $dependentFields = implode(', ', $this->dependentFields);
            return "The field {$field} must be present only when all the other fields are missing: {$dependentFields}.";
        }

        return true;
    }
}
