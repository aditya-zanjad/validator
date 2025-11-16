<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class RequiredUnless extends AbstractRule
{
    protected array $dependencyValues = [];

    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected string $dependency, string ...$dependencyValues)
    {
        if (empty($dependency) || empty($dependencyValues)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The rule required_if [{$currentClassName}] requires at least two parameters.");
        }

        $this->dependencyValues = $dependencyValues;
    }

    public function validate(mixed $value): bool
    {
        $dependencyActualValue = $this->input->get($this->dependency);

        if (\is_null($dependencyActualValue)) {
            return true;
        }

        $valueIsNull = \is_null($value);

        foreach ($this->dependencyValues as $dependencyValue) {
            if ($dependencyActualValue === $dependencyValue && !$valueIsNull) {
                $this->error = "The field :{field} is required when the field {$this->dependency} is set.";
                return false;
            }
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
