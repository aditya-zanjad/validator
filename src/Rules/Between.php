<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

use function AdityaZanjad\Validator\Utils\varSize;
use function AdityaZanjad\Validator\Utils\varFilterSize;

/**
 * @version 1.0
 */
class Between extends AbstractRule
{
    /**
     * @var array<string, int|float|string> $min
     */
    protected array $min = [
        'given'     =>  null,
        'actual'    =>  null
    ];

    /**
     * @var array<string, int|float|string> $max 
     */
    /**
     * @var array<string, int|float|string> $min
     */
    protected array $max = [
        'given'     =>  null,
        'actual'    =>  null
    ];

    /**
     * @var string $message
     */
    protected string $message;

    /**
     * Inject the dependencies required to execute the validation logic in this rule.
     *
     * @param mixed $givenSize
     */
    public function __construct(mixed $givenMinSize, mixed $givenMaxSize)
    {
        $this->min['given']     =   $givenMinSize;
        $this->min['actual']    =   varFilterSize($givenMinSize);

        if (\is_null($this->min['actual'])) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [between] must be either [INTEGER] or [FLOAT].");
        }

        $this->min['given']     =   $givenMaxSize;
        $this->max['actual']    =   varFilterSize($givenMaxSize);

        if (\is_null($this->min['actual'])) {
            throw new Exception("[Developer][Exception]: The parameters passed to the validation rule [between] must be either [INTEGER] or [FLOAT].");
        }

        if ($this->max['actual'] < $this->min['actual']) {
            throw new Exception("[Developer][Exception]: The validation rule [between] requires that the max size parameter be greater than or equal to the min size parameter.");
        }
    }

    /**
     * @inheritDoc
     */
    public function check(mixed $value): bool
    {
        $size = varSize($value); 

        if ($size < $this->min['actual'] || $size > $this->max['actual']) {
            $this->message = match (\gettype($value)) {
                'array'                                     =>  "The field :{field} must contain elements between the range {$this->min['given']} and {$this->max['given']}.",
                'string'                                    =>  "The field :{field} must contain characters between the range {$this->min['given']} and {$this->max['given']}.",
                'resource', 'float', 'double', 'integer'    =>  "The field :{field} must be between the range {$this->min['given']} and {$this->max['given']}.",
                default                                     =>  "The field :{field} must be between the range {$this->min['given']} and {$this->max['given']}."
            };

            return false;
        };

        return true;
    }
}
