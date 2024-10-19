<?php

namespace AdityaZanjad\Validator\Rules\Constraints;

use Closure;
use Exception;
use AdityaZanjad\Validator\Rules\Rule;
use AdityaZanjad\Validator\Interfaces\RequiredConstraint;

/**
 * Check whether the given attribute is a valid string or not.
 */
class RequiredIf extends Rule implements RequiredConstraint
{
    /**
     * To decide whether or not to execute certain code based on the value of this variable.
     *
     * @var bool $callbackIsNull
     */
    protected bool $callbackIsNull;

    /**
     * @var array<int|string, mixed> $data
     */
    protected array $data;

    /**
     * This constructor can be initialized to construct.
     *
     * @param null|\Closure $callback
     */
    public function __construct(protected ?Closure $callback)
    {
        $this->callbackIsNull = is_null($callback);
    }

    /**
     * @inheritDoc
     */
    public function check(string $attribute, mixed $value): bool|string
    {
        if (!is_null($this->callback)) {
            return call_user_func($this->callback, $attribute, $value);
        }

        // If the other constrained field is not present OR is set to NULL.
        if (!in_array($this->data['actual_value'], $this->data['given_values'])) {
            return true;
        }

        // If the other field is present, but the field currently being validated is not present OR is an empty value.
        if (empty($value) && !in_array($value, [0, false, '0', 'false'], true)) {
            return "The field {$attribute} is required when the field {$this->data['other_field']} is set to {$this->data['actual_value']}";
        }

        return true;
    }
}
