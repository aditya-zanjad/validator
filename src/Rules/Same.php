<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Same extends AbstractRule
{
    /**
     * @var string $data
     */
    protected string $data;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        $dataToMatchWith = $this->data[0];

        if ($value != $dataToMatchWith) {
            return "The field {$field} must be equal to {$dataToMatchWith}.";
        }

        return true;
    }
}
