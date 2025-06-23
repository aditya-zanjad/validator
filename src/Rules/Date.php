<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Throwable;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varEvaluateType;

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
     * The error message to return on validation failure.
     *
     * @var string $error
     */
    protected string $error = 'The field :{field} must be a valid date.';

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
        if (!\is_string($value)) {
            return $this->error;
        }

        if (!empty($this->format) && DateTime::createFromFormat($this->format, (string) $value) === false) {
            return "The field :{field} must be a valid date in the format {$this->format}.";
        }

        try {
            $evaluatedValue = varEvaluateType($value);

            switch (\gettype($evaluatedValue)) {
                case 'string':
                    new DateTime($evaluatedValue);
                    break;

                case 'integer':
                    new DateTime("@{$evaluatedValue}");
                    break;

                default:
                    return $this->error;
            }
        } catch (Throwable $e) {
            // var_dump($e); exit;
            return $this->error;
        }

        return true;
    }
}
