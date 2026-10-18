<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;
use AdityaZanjad\Validator\Interfaces\MandatoryRule;

class ReqUnless extends AbstractRule implements MandatoryRule
{
    protected array $dependencyValues = [];

    protected string $error = 'The field :{field} is invalid';

    public function __construct(protected string $dependency, string ...$dependencyValues)
    {
        if (empty($dependency) || empty($dependencyValues)) {
            $currentClassName = static::class;
            throw new Exception("[Developer][Exception]: The rule required_unless [{$currentClassName}] requires at least two parameters.");
        }

        $this->dependencyValues = $dependencyValues;
    }

    public function validate(mixed $value): bool
    {
        $dependencyActualValue = $this->input->get($this->dependency);

        $valueIsNull = \is_null($value);

        foreach ($this->dependencyValues as $dependencyValue) {
            if ($dependencyActualValue === $dependencyValue) {
                return true;
            }
        }

        if ($valueIsNull) {
            $this->error = "The field :{field} is required unless the field {$this->dependency} is in the specified values.";
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return $this->error;
    }
}
