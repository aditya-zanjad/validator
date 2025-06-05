<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Max extends AbstractRule
{
    /**
     * @var int $maxAllowedSize
     */
    protected int $maxAllowedSize;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param   int|string $maxAllowedSize
     * 
     * @throws  \Exception
     */
    public function __construct($maxAllowedSize)
    {
        $filtered = filter_var($maxAllowedSize, FILTER_VALIDATE_INT);

        if (!$filtered && $filtered !== 0) {
            throw new Exception("[Developer][Exception]: The value passed to the validation rule [max] must be an integer.");
        }

        $this->maxAllowedSize = (int) $maxAllowedSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        if (varSize($value) > $this->maxAllowedSize) {
            return "The field {$field} cannot be more than {$this->maxAllowedSize}.";
        }

        return true;
    }
}
