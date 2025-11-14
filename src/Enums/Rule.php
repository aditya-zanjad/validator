<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Arr;
use AdityaZanjad\Validator\Rules\Boolean;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Integer;
use AdityaZanjad\Validator\Rules\Json;
use AdityaZanjad\Validator\Rules\Num;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\RequiredWith;
use AdityaZanjad\Validator\Rules\RequiredWithAll;
use AdityaZanjad\Validator\Rules\RequiredWithout;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;
use AdityaZanjad\Validator\Rules\Str;
use AdityaZanjad\Validator\Rules\Url;

class Rule
{
    public const STR                =   Str::class;
    public const ARR                =   Arr::class;
    PUBLIC const URL                =   Url::class;
    public const BOOL               =   Boolean::class;
    public const INT                =   Integer::class;
    public const NUM                =   Num::class;
    public const JSON               =   Json::class;
    public const EMAIL              =   Email::class;
    public const REQ                =   Required::class;
    public const REQ_WITH           =   RequiredWith::class;
    public const REQ_WITH_ALL       =   RequiredWithAll::class;
    public const REQ_WITHOUT        =   RequiredWithout::class;
    public const REQ_WITHOUT_ALL    =   RequiredWithoutAll::class;


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