<?php

namespace AdityaZanjad\Validator\Rules\Constraints;

use Closure;
use AdityaZanjad\Validator\Rules\Rule;
use AdityaZanjad\Validator\Interfaces\RequiredConstraint;

use function AdityaZanjad\Validator\Utils\filter_values;

/**
 * Check whether the given attribute is a valid string or not.
 */
class RequiredIf extends Rule implements RequiredConstraint
{
    /**
     * This constructor can be initialized to construct.
     *
     * @param array<int, mixed>|\Closure $entity
     */
    public function __construct(protected array|Closure $entity)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (is_callable($this->entity)) {
            return call_user_func($this->entity, $attribute, $value);
        }

        $dependentFieldValue    =   $this->input->get(array_splice($this->entity, 0, 1)[0]);
        $this->entity           =   filter_values($this->entity);

        // If the current field is not present or is NULL, but the other dependent field is present.
        if (is_null($value)) {
            return "The field {$attribute} is required when the field {$this->entity[0]} is set to {$dependentFieldValue}";
        }

        return true;
    }
}
