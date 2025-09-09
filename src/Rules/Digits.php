<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varDigits;

/**
 * @version 1.0
 */
class Digits extends AbstractRule
{
    /**
     * @var int $validDigits
     */
    protected int $validDigits;

    /**
     * Inject the data required to perform validation.
     *
     * @param int|string $validDigits
     *
     * @throws \Exception
     */
    public function __construct(mixed $validDigits)
    {
        if (\filter_var($validDigits, FILTER_VALIDATE_INT) === false) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits] must be a valid integer.");
        }

        if ($validDigits < 1) {
            throw new Exception("[Developer][Exception]: The parameter passed to the validation rule [digits] must be greater than 0.");
        }

        $this->validDigits = (int) $validDigits;
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        return varDigits($value) === $this->validDigits;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return "The field :{field} must contain exactly {$this->validDigits} digits.";
    }
}
