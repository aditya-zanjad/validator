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
        'ipv4'          =>  FILTER_FLAG_IPV4,
        'ipv6'          =>  FILTER_FLAG_IPV6,
        'no_private'    =>  FILTER_FLAG_NO_PRIV_RANGE,
        'no_reserved'   =>  FILTER_FLAG_NO_RES_RANGE
    ];

    protected array $options;

    public function __construct(string ...$options)
    {
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!is_string($value)) {
            return 'The field :{field} must be a valid IP address.';
        }

        if (empty($this->options) && filter_var($value, FILTER_VALIDATE_IP) === false) {
            return 'The field :{field} must be a valid IP address.';
        }

        // If any of the options are provided, ensure that the provided options are valid.
        $options        =   array_values(array_unique($this->options));
        $invalidOptions =   array_diff($this->validOptions, $options);

        if (!empty($invalidOptions)) {
            $implodedInvalidOptions = implode(', ', $invalidOptions);
            throw new Exception("[Developer][Exception]: The validation rule [ip_address] is supplied with the invalid options: {$implodedInvalidOptions}");
        }

        $appliedOptions = array_reduce($options, fn ($carryOver, $option) => $carryOver | $option);

        if (filter_var($value, FILTER_VALIDATE_IP, $appliedOptions) === false) {
            return 'The field :{field} must be a valid IP address.';
        }

        return true;
    }
}
