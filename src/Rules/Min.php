<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Min extends AbstractRule
{
    /**
     * @var int $minValidSize
     */
    protected int $minValidSize;

    /**
     * @var string $message
     */
    protected string $message;

    /**
     * @param int $minValidSize
     */
    public function __construct(int|string $minValidSize)
    {
        $this->minValidSize = (int) $minValidSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        $size = varSize($value);

        if ($size < $this->minValidSize) {
            $this->message = match (gettype($value)) {
                'array'                         =>  "The array {$field} must contain at least {$this->minValidSize} elements.",
                'string'                        =>  "The string {$field} must contain at least {$this->minValidSize} characters.",
                'resource'                      =>  "The file {$field} must be at least {$this->minValidSize}.",
                'integer', 'float', 'double'    =>  "The field {$field} must be at least {$this->minValidSize}.",
                default                         =>  "The field {$field} must be at least {$this->minValidSize}.",
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
