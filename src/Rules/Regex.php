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
     * @var string $regex
     */
    protected string $regex;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param string $regex
     */
    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return !in_array(preg_match($this->regex, $value, $matches), [0, false]);
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must match the regular expression {$this->regex}.";
    }
}
