<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

class Rule
{
    public const STRING = 'string';

    public static function valueOf(string $name): null|string
    {
        $name               =   \strtoupper($name);
        $currentClassName   =   static::class;

        if (!\defined("{$currentClassName}::{$name}")) {
            return null;
        }
        
        return "{$currentClassName}::{$name}";
    }
}