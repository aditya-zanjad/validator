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
    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        $result = \preg_match($this->regex, (string) $value);

        if ($result === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [regex] might be invalid.");
        }

        return $result > 0;
    }

    /**
     * @inheritDoc
     */
    public function error(): string
    {
        return "The field :{field} must match the regular expression: {$this->regex}.";
    }
}
