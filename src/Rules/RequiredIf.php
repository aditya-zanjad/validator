<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\RequisiteRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class RequiredIf extends AbstractRule implements RequisiteRule
{
    /**
     * The data on which we want to operate on the determine if the validation rules passes or fails.
     *
     * If it's an array, it'll contain data for a particular field against which we want to validate
     * the current input field. If it's a callback, then the custom logic written inside of it
     * will be executed to determine if the validation rule passes or fails.
     *
     * @var array<int, string>|callable($field, $value, \AdityaZanjad\Validator\Validator\Input): bool|string $entity
     */
    protected $entity;

    /**
     * Inject necessary dependencies into the class.
     *
     * @param callable($field, $value, \AdityaZanjad\Validator\Validator\Input $input): bool|string $entity
     */
    public function __construct(...$entity)
    {
        $this->entity = $entity;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (is_callable($this->entity[0])) {
            return call_user_func($this->entity[0], $field, $value, $this->input);
        }

        // Obtain the necessary data required to validate the field.
        $primaryField       =   $this->entity[0];
        $primaryFieldValue  =   $this->input->get($primaryField);
        $primaryValidValues =   array_slice($this->entity, 1);

        $primaryValidValues = array_map(function ($value) {
            return varEvaluateType($value);
        }, $primaryValidValues);

        if (!in_array($primaryFieldValue, $primaryValidValues, true)) {
            return true;
        }

        // If the dependent field has one of the specified values & if the current field is not present.
        if ($this->input->notNull($field)) {
            return true;
        }

        if (is_null($primaryFieldValue)) {
            $primaryFieldValue = 'null';
        }

        return "The field {$field} is required when the field {$primaryField} is set to {$primaryFieldValue}.";
    }
}
