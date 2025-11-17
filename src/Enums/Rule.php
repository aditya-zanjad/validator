<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Arr;
use AdityaZanjad\Validator\Rules\ArrBetween;
use AdityaZanjad\Validator\Rules\ArrGreaterThan;
use AdityaZanjad\Validator\Rules\ArrGt;
use AdityaZanjad\Validator\Rules\ArrLessThan;
use AdityaZanjad\Validator\Rules\ArrLt;
use AdityaZanjad\Validator\Rules\ArrMax;
use AdityaZanjad\Validator\Rules\ArrMin;
use AdityaZanjad\Validator\Rules\ArrRange;
use AdityaZanjad\Validator\Rules\Boolean;
use AdityaZanjad\Validator\Rules\Date;
use AdityaZanjad\Validator\Rules\DateBetween;
use AdityaZanjad\Validator\Rules\DateGreaterThan;
use AdityaZanjad\Validator\Rules\DateLessThan;
use AdityaZanjad\Validator\Rules\DateMax;
use AdityaZanjad\Validator\Rules\DateMin;
use AdityaZanjad\Validator\Rules\DateRange;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Integer;
use AdityaZanjad\Validator\Rules\Json;
use AdityaZanjad\Validator\Rules\Number;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\RequiredWith;
use AdityaZanjad\Validator\Rules\RequiredWithAll;
use AdityaZanjad\Validator\Rules\RequiredWithout;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;
use AdityaZanjad\Validator\Rules\Str;
use AdityaZanjad\Validator\Rules\StrBetween;
use AdityaZanjad\Validator\Rules\StrLower;
use AdityaZanjad\Validator\Rules\StrMax;
use AdityaZanjad\Validator\Rules\StrMin;
use AdityaZanjad\Validator\Rules\StrRange;
use AdityaZanjad\Validator\Rules\StrUpper;
use AdityaZanjad\Validator\Rules\Url;

class Rule
{
    public const STR                =   Str::class;
    public const ARR                =   Arr::class;
    PUBLIC const URL                =   Url::class;
    public const BOOL               =   Boolean::class;
    public const INT                =   Integer::class;
    public const NUM                =   Number::class;
    public const JSON               =   Json::class;
    public const EMAIL              =   Email::class;
    public const REQ                =   Required::class;
    public const REQ_WITH           =   RequiredWith::class;
    public const REQ_WITH_ALL       =   RequiredWithAll::class;
    public const REQ_WITHOUT        =   RequiredWithout::class;
    public const REQ_WITHOUT_ALL    =   RequiredWithoutAll::class;
    public const DATE               =   Date::class;
    public const DATE_MIN           =   DateMin::class;
    public const DATE_MAX           =   DateMax::class;
    public const DATE_RANGE         =   DateRange::class;
    public const DATE_LT            =   DateLessThan::class;
    public const DATE_GT            =   DateGreaterThan::class;
    public const STR_MIN            =   StrMin::class;
    public const STR_MAX            =   StrMax::class;
    public const STR_RANGE          =   StrRange::class;
    public const DATE_BT            =   DateBetween::class;
    public const STR_BT             =   StrBetween::class;
    public const STR_UP             =   StrUpper::class;
    public const STR_LOW            =   StrLower::class;
    public const ARR_MIN            =   ArrMin::class;
    public const ARR_MAX            =   ArrMax::class;
    public const ARR_LT             =   ArrLessThan::class;
    public const ARR_GT             =   ArrGreaterThan::class;
    public const ARR_RANGE          =   ArrRange::class;
    public const ARR_BETWEEN        =   ArrBetween::class;   

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