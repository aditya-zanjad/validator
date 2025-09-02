<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class TypeBoolean extends AbstractRule
{
    /**
     * @var array<int, string> $validValues
     */
    protected array $validValues = ['bool', 'int', 'str_bool', 'str_int', 'true/false', '1/0', 'on/off', 'yes/no'];

    /**
     * @var array<int, string> $allowedValues
     */
    protected array $allowedValues = [];

    /**
     * @param   string ...$allowedValues
     * 
     * @throws  \Exception
     */
    public function __construct(string ...$allowedValues)
    {
        if (empty($allowedValues)) {
            return;
        }

        foreach ($allowedValues as $allowedValue) {
            if (!\in_array($allowedValue, $this->validValues)) {
                throw new Exception('[Developer][Exception]: The validation rule [boolean] considers only these parameters as valid: "bool", "int", "str_bool", "str_int", "on/off", "yes/no".');
            }
        }

        $this->allowedValues = \array_unique($allowedValues);
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool
    {
        if (\is_null($value)) {
            return false;
        }

        if (empty($this->allowedValues)) {
            return \filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) !== null;
        }

        foreach ($this->allowedValues as $allowedValue) {
            $result = match ($allowedValue) {
                'bool'          =>  \in_array($value, [true, false], true),
                'int'           =>  \in_array($value, [0, 1], true),
                'true/false'    =>  \in_array($value, [true, false], true) || (\is_string($value) && (\strcasecmp($value, 'true') === 0 || \strcasecmp($value, 'false') === 0)),
                '1/0'           =>  \in_array($value, [0, 1, '0', '1'], true),
                'on/off'        =>  \is_string($value) && (\strcasecmp($value, 'on') === 0 || \strcasecmp($value, 'off') === 0),
                'yes/no'        =>  \is_string($value) && (\strcasecmp($value, 'yes') === 0 || \strcasecmp($value, 'no') === 0),
                'str_bool'      =>  \is_string($value) && (\strcasecmp($value, 'true') === 0 || \strcasecmp($value, 'false') === 0),
                'str_int'       =>  \in_array($value, ['0', '1'], true),
            };

            if ($result) {
                return true;
            } 
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a boolean value.';
    }
}
