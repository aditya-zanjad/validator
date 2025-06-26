<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class Date extends AbstractRule
{
    /**
     * @var string $format
     */
    protected string $format;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $format
     */
    public function __construct(string ...$formatChars)
    {
        $this->format = !empty($formatChars) ? \trim(\implode(',', $formatChars)) : '';
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        $givenDateTime = parseDateTime($value, $this->format);

        if ($givenDateTime !== false) {
            return true;
        }

        return !empty($this->format) 
            ? 'The field :{field} must be a valid date.' 
            : "The field :{field} must be a valid date with the format: {$this->format}";
    }
}
