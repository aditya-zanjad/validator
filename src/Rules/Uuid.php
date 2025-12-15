<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

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
    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }
        
        $result = \preg_match($this->makeRegex($this->version), $value);

        if ($result === false) {
            throw new Exception("[Developer][Exception]: [INTERNAL DEVELOPMENT ERROR]: The validation rule [uuid] might be using an invalid regex for validating the data.");
        }

        return $result > 0;
    }

    /**
     * @return string
     */
    public function error(): string
    {
        return "The field :{field} must be a valid UUID {$this->version} string.";
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
        $version    =   \str_replace(' ', '', $version);
        $version    =   \strtolower($version);

        return match ($version) {
            'v1'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-1([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v2'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-2([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v3'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-3([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v4'    =>  '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            'v5'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-5([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v6'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-6([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v7'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-7([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            'v8'    =>  '/^([0-9a-f]{8})-([0-9a-f]{4})-8([0-9a-f]{3})-([89ab][0-9a-f]{3})-([0-9a-f]{12})$/i',
            default =>  throw new Exception("[Developer][Exception]: The validation rule [uuid] is supplied with an invalid/unsupported UUID version: [{$version}]")
        };
    }
}
