<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
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
            return "The field :{field} must be a valid UUID {$this->version} string.";
        }

        $regex = $this->makeRegex($this->version);

        if (\preg_match($regex, $value) === false) {
            return "The field :{field} must be a valid UUID {$this->version} string.";
        }

        return true;
    }

    /**
     * Depending on the value of the version, make the regular expression required for the validation.
     *
     * @param string $version
     * 
     * @throws \Exception
     * 
     * @return string
     */
    protected function makeRegex(string $version): string
    {
        $regex = null;

        switch (strtolower($version)) {
            case 'v1':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-1([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v2':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-2([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v3':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-3([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v5':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-5([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v6':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-6([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v7':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-7([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v8':
                $regex = '/^([0-9a-f]{8})-([0-9a-f]{4})-8([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i';
                break;

            case 'v4':
                $regex = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
                break;

            default:
                throw new Exception("[Developer][Exception]: The validation rule [uuid] is supplied with an invalid UUID version: [{$version}]");
        }

        return $regex;
    }
}
