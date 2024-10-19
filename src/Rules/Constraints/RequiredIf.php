<?php

namespace AdityaZanjad\Validator\Rules\Constraints;

use Closure;
use AdityaZanjad\Validator\Rules\Rule;
use AdityaZanjad\Validator\Interfaces\RequiredConstraint;

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

        [$dependentFieldValue, $this->entity] = $this->filterGivenValues($this->entity);

        // If the current field is not present or is NULL, but the other dependent field is present.
        if (is_null($value)) {
            return "The field {$attribute} is required when the field {$this->entity[0]} is set to {$dependentFieldValue}";
        }

        return true;
    }

    /**
     * Filter the given values of the field to their appropriate data types on which the validation of current field is dependent.
     *
     * @param array<int, string> $entity
     *
     * @return array<int, string|array<int, mixed>>
     */
    protected function filterGivenValues(array $entity)
    {
        // Get value of the field on which the validation of the current field is dependent.
        $dependentFieldValue    =   array_splice($entity, 0, 1);
        $dependentFieldValue    =   $this->input->get($dependentFieldValue[0]);

        return [
            $dependentFieldValue,
            array_map(function ($givenValue) {
                $givenValue = filter_var($givenValue, FILTER_DEFAULT, [
                    FILTER_VALIDATE_BOOL | FILTER_VALIDATE_INT | FILTER_VALIDATE_FLOAT
                ]);

                if ($givenValue === 'null') {
                    return null;
                }

                return $givenValue;
            }, $entity)
        ];
    }
}
