<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use AdityaZanjad\Validator\Base\AbstractRule;

/**
 * @version 1.0
 */
class Uuid extends AbstractRule
{
    /**
     * To determine for which version we want to verify against.
     *
     * @var string $version
     */
    protected string $version;

    /**
     * @param string $version
     */
    public function __construct(string $version = 'v4')
    {
        $this->version = $version;
    }

    /**
     * @inheritDoc
     */
    public function check(string $field, $value)
    {
        if (!\is_string($value)) {
            return 'The field :{field} must be a valid UUID.';
        }

        $givenValueIsValidUuid = false;

        switch ($this->version) {
            case 'v4':
                $givenValueIsValidUuid = \preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $value) !== false;
                break;

            default:
                $givenValueIsValidUuid = false;
                break;
        }

        if ($givenValueIsValidUuid) {
            return 'The field :{field} must be a valid UUID.';
        }

        return true;
    }
}
