<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class LessThanOrEqualTo extends AbstractRule
{
    /**
     * @var array<int, mixed> $entities
     */
    protected array $entities;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed ...$entities
     */
    public function __construct(mixed ...$entities)
    {
        $this->entities = $entities;
    }

    /**
     * @inheritDoc
     */
    public function check(string $fieldPath, mixed $value): bool|string
    {
        foreach ($this->entities as $entity) {
            if ($value > $entity) {
                return "The field {$fieldPath} must be less than or equal to {$entity}.";
            }
        }

        return true;
    }
}
