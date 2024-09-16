<?php

namespace AdityaZanjad\Validator\Rules\Required;

use AdityaZanjad\Validator\Rules\Required\Base\RequiredRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class RequiredWithout extends RequiredRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        foreach ($this->data as $field => $value) {
            if (!is_null($value)) {
                return "The field {$attribute} must be present without the attribute {$field}.";
            }
        }

        $fields =   implode(', ', array_keys($this->data));
        $fields =   rtrim($fields);

        if (count($this->data) > 1) {
            return "The attribute {$attribute} must be present without the attributes [{$fields}].";
        }
        
        return "The attribute {$attribute} must be present without the attribute [{$fields}].";
    }
}
