<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Rules;

use Throwable;
use Exception;
use AdityaZanjad\Validator\Base\AbstractRule;

class Url extends AbstractRule
{
    protected array $validOptions = [
        'path'  =>  'path',
        'query' =>  'query'
    ];

    protected array $suppliedOptions = [];

    protected string $error = 'The field :{field} must be a valid URL.';

    public function __construct(string ...$options)
    {
        if (\count($options) > 2) {
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] accepts only two parameters.");
        }

        if (!empty($options) && !empty(\array_diff($options, $this->validOptions))) {
            $validOptions = \implode(', ', $this->validOptions);
            throw new Exception("[Developer][Exception]: The validation rule [" . static::class . "] accepts only these options: {$validOptions}");
        }

        $this->suppliedOptions = $options;
    }

    public function validate(mixed $value): bool
    {
        if (!\is_string($value)) {
            return false;
        }

        $value = \trim($value);

        if (empty($value)) {
            return false;
        }

        return $this->validateWithCorePhp($value);

        return \class_exists(\League\Uri\Uri::class)
            ? $this->validateWithLeagueUriPackage($value)
            : $this->validateWithCorePhp($value);
    }

    protected function validateWithCorePhp(string $value): bool
    {
        if (empty($this->suppliedOptions)) {
            return \filter_var($value, FILTER_VALIDATE_URL) !== false;
        }

        if (\in_array($this->validOptions['path'], $this->suppliedOptions) && \filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false) {
            return false;
        }

        if (\in_array($this->validOptions['query'], $this->suppliedOptions) && \filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false) {
            return false;
        }

        return true;
    }

    protected function validateWithLeagueUriPackage(string $value): bool
    {
        try {
            if (!\is_null(\League\Uri\Urn::parse($value))) {
                return true;
            }

            $uri = \League\Uri\Uri::new($value);

            if (empty($uri->getScheme())) {
                return false;
            }

            if (empty($uri->getHost())) {
                return false;
            }

            if (\in_array($this->validOptions['path'], $this->suppliedOptions) && empty($uri->getPath())) {
                return false;
            }

            if (\in_array($this->validOptions['query'], $this->suppliedOptions) && empty($uri->getQuery())) {
                return false;
            }
        } catch (\League\Uri\Exceptions\SyntaxError $err) {
            // var_dump($err); exit;
            return false;
        } catch (Throwable $err) {
            // var_dump($err); exit;
            return false;
        }

        return true;
    }

    public function error(): string
    {
        return 'The field :{field} must be a valid URL.';
    }
}
