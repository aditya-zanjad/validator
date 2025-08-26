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
     * @var string $message
     */
    protected string $message;

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
    public function check(string $field, $value): bool
    {
        if (varSize($value) > $this->maxAllowedSize) {
            $this->message = match (\gettype($value)) {
                'array'                         =>  "The array {$field} must not contain more than {$this->maxAllowedSize} elements.",
                'string'                        =>  "The string {$field} must not contain more than {$this->maxAllowedSize} characters.",
                'resource'                      =>  "The size of the file {$field} must not be more than {$this->maxAllowedSize}.",
                'integer', 'float', 'double'    =>  "The number {$field} must not be more than {$this->maxAllowedSize}.",
                default                         =>  "The size of the field {$field} must not be more than {$this->maxAllowedSize}.",
            };

            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return $this->message;
    }
}
