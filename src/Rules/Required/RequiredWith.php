<?php

namespace AdityaZanjad\Validator\Rules\Required;

use AdityaZanjad\Validator\Rules\Required\Base\RequiredRule;

/**
 * Check whether the given attribute is a valid string or not.
 */
class RequiredWith extends RequiredRule
{
    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        foreach ($this->data as $field => $value) {
            if (is_null($value)) {
                return true;
            }
        }
        
        if (!is_null($value)) {
            return true;
        }

        $fields =   implode(', ', array_keys($this->data));
        $fields =   rtrim($fields);

        if (count($this->data) > 1) {
            return "The attribute {$attribute} is required when the attributes [{$fields}] are present";
        }
        
        return "The attribute {$attribute} is required when the attribute [{$fields}] is present";
    }
}
