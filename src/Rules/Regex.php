<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Regex extends AbstractRule
{
    /**
     * @var array<int, string> $regexes
     */
    protected array $regexes;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param int $regexes
     */
    public function __construct(string ...$regexes)
    {
        $this->regexes = $regexes;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, mixed $value): bool|string
    {
        // To hold the regular expression matches for iteration.
        $matches = [];

        foreach ($this->regexes as $regex) {
            preg_match($regex, $value, $matches);

            if (empty($matches)) {
                return "The field {$field} must match the regular expression {$regex}.";
            }

            // Empty up the array containing the regular expressions matches for the next iteration.
            $matches = [];
        }

        return true;
    }
}
