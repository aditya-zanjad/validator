<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class IpAddress extends AbstractRule
{
    /**
     * Options that are considered valid when validating the IP address.
     *
     * @var array<string, int> $validOptions
     */
    protected array $validOptions = [
        'v4'            =>  FILTER_FLAG_IPV4,
        'v6'            =>  FILTER_FLAG_IPV6,
        'public'        =>  FILTER_FLAG_NO_PRIV_RANGE,
        'unreserved'    =>  FILTER_FLAG_NO_RES_RANGE
    ];

    /**
     * @var array<int, string> $givenOptions
     */
    protected array $givenOptions;

    /**
     * @param string ...$givenOptions
     */
    public function __construct(string ...$givenOptions)
    {
        $this->givenOptions = $givenOptions;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value): bool
    {
        return \filter_var($value, FILTER_VALIDATE_IP, $this->prepareOptions()) !== false;
    }

    /**
     * @inheritDoc
     */
    public function message(): string
    {
        return 'The field :{field} must be a valid IP address.';
    }

    /**
     * Prepare the user-supplied validation configuration options.
     *
     * @return int
     */
    protected function prepareOptions(): int
    {
        if (empty($this->givenOptions)) {
            return 0;
        }

        // If any of the options are provided, ensure that the provided options are valid.
        $options        =   \array_values($this->givenOptions);
        $invalidOptions =   \array_diff($options, \array_keys($this->validOptions));

        if (!empty($invalidOptions)) {
            $implodedInvalidOptions = \implode(', ', $invalidOptions);
            throw new Exception("[Developer][Exception]: The validation rule [ip_address] is supplied with the invalid options: {$implodedInvalidOptions}");
        }

        $options        =   \array_unique($options);
        $appliedOptions =   0;

        foreach ($options as $option) {
            $appliedOptions |= $this->validOptions[$option];
        }

        return $appliedOptions;
    }
}
