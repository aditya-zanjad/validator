<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
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
    public function check(mixed $value): bool
    {
        $result = \preg_match($this->regex, (string) $value);

        if ($result === false) {
            throw new Exception("[Developer][Exception]: The validation rule [regex] is passed with an invalid regular expression for the field [{field}] at index [{index}].");
        }

        return $result > 0;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must match the regular expression: {$this->regex}.";
    }
}
