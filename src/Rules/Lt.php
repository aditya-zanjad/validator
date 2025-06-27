<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;

/**
 * @version 1.0
 */
class Lt extends AbstractRule
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
        if (filter_var($comparingSize, FILTER_VALIDATE_INT) === false && filter_var($comparingSize, FILTER_VALIDATE_FLOAT) === false) {
            throw new Exception("[Developer][Exception]: The validation rule [lt] requires its parameter to be either an Integer or a Float.");
        }

        $this->comparingSize = $comparingSize;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (varSize($value) >= $this->comparingSize) {
            return "The field :{field} must be less than {$this->comparingSize}";
        }

        return true;
    }
}
