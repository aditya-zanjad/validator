<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Throwable;
use AdityaZanjad\Validator\Base\AbstractRule;

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
    public function __construct(string $format = '')
    {
        $this->format = $format;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be a valid date.';
        }

        if (!empty($this->format) && DateTime::createFromFormat($this->format, $value) === false) {
            return "The field :{field} must be a valid date in the format {$this->format}.";
        }

        try {
            new DateTime($value);
        } catch (Throwable $e) {
            // var_dump($e); exit;
            return 'The field :{field} must be a valid date.';
        }

        return true;
    }
}
