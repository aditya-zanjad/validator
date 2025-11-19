<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Input
{
    public function __construct(protected array $input)
    {
        //        
    }

    public function all(): array
    {
        return $this->input;
    }

    public function dotPaths(): array
    {
        $path       =   [];
        $result     =   [];
        $iterator   =   new RecursiveArrayIterator($this->input);
        $iterator   =   new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $key => $value) {
            $path[$iterator->getDepth()] = $key;

            if (!\is_array($value)) {
                $finalPath          =   \array_slice($path, 0, $iterator->getDepth() + 1);
                $finalPath          =   \implode('.', $finalPath);
                $result[$finalPath] =   $value;
            }
        }

        return $result;
    }

    public function set(string $path, mixed $value): static
    {
        $ref        =   &$this->input;
        $keys       =   \explode('.', $path);
        $lastKey    =   \array_pop($keys);

        foreach ($keys as $key) {
            if (!\array_key_exists($key, $ref) || !\is_array($ref)) {
                $ref[$key] = [];
            }

            $ref = &$ref[$key];
        }

        $ref[$lastKey] = $value;
        return $this;
    }

    public function get(string $path): mixed
    {
        $ref        =   &$this->input;
        $keys       =   \explode('.', $path);
        $lastKey    =   \array_pop($keys);

        foreach ($keys as $key) {
            if (!isset($ref[$key]) || !\is_array($ref[$key])) {
                return null;
            }

            $ref = &$ref[$key];
        }

        return $ref[$lastKey] ?? null;
    }

    public function isset(string $path): bool
    {
        $ref        =   &$this->input;
        $keys       =   \explode('.', $path);
        $lastKey    =   \array_pop($keys);

        foreach ($keys as $key) {
            if (!isset($ref[$key]) || !\is_array($ref[$key])) {
                return false;
            }

            $ref = &$ref[$key];
        }

        return isset($ref[$lastKey]);
    }
}