<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Gte extends AbstractRule
{
    /**
     * @var int|string $comparingSize
     */
    protected $comparingSize;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed ...$comparingSize
     */
    public function __construct($comparingSize)
    {
        $comparingSizeIsInvalid = filter_var($comparingSize, FILTER_VALIDATE_INT) === false 
            && filter_var($comparingSize, FILTER_VALIDATE_FLOAT) === false
            && !is_string($comparingSize);

        if ($comparingSizeIsInvalid) {
            throw new Exception("[Developer][Exception]: The validation rule [gte] requires its parameter to be either an Integer, Float or a String.");
        }

        $this->comparingSize = $comparingSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (varSize($value) < $this->comparingSize) {
            return "The field :{field} must be greater than or equal to {$this->comparingSize}";
        }

        return true;
    }
}
