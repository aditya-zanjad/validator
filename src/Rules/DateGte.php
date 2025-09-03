<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use DateTime;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\parseDateTime;

/**
 * @version 1.0
 */
class DateGte extends AbstractRule
{
    /**
     * @var string $gteDateString
     */
    protected string $gteDateString;

    /**
     * @var bool|\DateTime $gteDate
     */
    protected $gteDate;

    /**
     * Inject the data required to perform validation.
     *
     * @param string $gteDateString
     */
    public function __construct(string $gteDateString)
    {
        $this->gteDateString    =   $gteDateString;
        $this->gteDate          =   parseDateTime($gteDateString);

        if ($this->gteDate === false) {
            throw new Exception("[Developer][Exception]: The validation rule [date_gte] must be provided with a valid after/equal date.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $givenDateTime = parseDateTime($value);
        return $givenDateTime !== false && $givenDateTime >= $this->gteDate;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must be a valid date greater than or equal to: {$this->gteDateString}";
    }
}
