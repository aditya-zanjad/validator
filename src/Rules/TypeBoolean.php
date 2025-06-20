<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

/**
 * @version 1.0
 */
class TypeBoolean extends AbstractRule
{
    /**
     * @var array<int, bool|int|string> $validBooleans
     */
    protected array $validBooleans = [true, false, 'true', 'false', 0, 1, '1', '0', 'on', 'off'];

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!in_array(varEvaluateType($value), $this->validBooleans)) {
            return "The field {$field} must be a boolean value.";
        }

        return true;
    }
}
