<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Arr;
use AdityaZanjad\Validator\Rules\ArrMax;
use AdityaZanjad\Validator\Rules\ArrMin;
use AdityaZanjad\Validator\Rules\ArrFilled;
use AdityaZanjad\Validator\Rules\ArrBetween;
use AdityaZanjad\Validator\Rules\ArrGreaterThan;
use AdityaZanjad\Validator\Rules\ArrLessThan;
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
use AdityaZanjad\Validator\Rules\File;
use AdityaZanjad\Validator\Rules\FileBetween;
use AdityaZanjad\Validator\Rules\FileFilled;
use AdityaZanjad\Validator\Rules\FileGreaterThan;
use AdityaZanjad\Validator\Rules\FileLessThan;
use AdityaZanjad\Validator\Rules\FileMax;
use AdityaZanjad\Validator\Rules\FileMin;
use AdityaZanjad\Validator\Rules\FileRange;
use AdityaZanjad\Validator\Rules\Integer;
use AdityaZanjad\Validator\Rules\IpAddress;
use AdityaZanjad\Validator\Rules\Json;
use AdityaZanjad\Validator\Rules\MacAddress;
use AdityaZanjad\Validator\Rules\Number;
use AdityaZanjad\Validator\Rules\NumBetween;
use AdityaZanjad\Validator\Rules\NumGreaterThan;
use AdityaZanjad\Validator\Rules\NumLessThan;
use AdityaZanjad\Validator\Rules\NumMax;
use AdityaZanjad\Validator\Rules\NumMin;
use AdityaZanjad\Validator\Rules\NumRange;
use AdityaZanjad\Validator\Rules\Req;
use AdityaZanjad\Validator\Rules\ReqWith;
use AdityaZanjad\Validator\Rules\ReqWithAll;
use AdityaZanjad\Validator\Rules\ReqWithout;
use AdityaZanjad\Validator\Rules\ReqWithoutAll;
use AdityaZanjad\Validator\Rules\Same;
use AdityaZanjad\Validator\Rules\Str;
use AdityaZanjad\Validator\Rules\StrBetween;
use AdityaZanjad\Validator\Rules\StrFilled;
use AdityaZanjad\Validator\Rules\StrGreaterThan;
use AdityaZanjad\Validator\Rules\StrLessThan;
use AdityaZanjad\Validator\Rules\StrLower;
use AdityaZanjad\Validator\Rules\StrMax;
use AdityaZanjad\Validator\Rules\StrMin;
use AdityaZanjad\Validator\Rules\StrRange;
use AdityaZanjad\Validator\Rules\StrRegex;
use AdityaZanjad\Validator\Rules\StrSize;
use AdityaZanjad\Validator\Rules\StrUpper;
use AdityaZanjad\Validator\Rules\Ulid;
use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Rules\Uuid;

class Rule
{
    public const ARR                =   Arr::class;
    public const INT                =   Integer::class;
    public const NUM                =   Number::class;
    public const REQ                =   Req::class;
    public const STR                =   Str::class;
    public const URL                =   Url::class;
    public const BOOL               =   Boolean::class;
    public const JSON               =   Json::class;
    public const EMAIL              =   Email::class;
    public const REQ_WITH           =   ReqWith::class;
    public const REQ_WITH_ALL       =   ReqWithAll::class;
    public const REQ_WITHOUT        =   ReqWithout::class;
    public const REQ_WITHOUT_ALL    =   ReqWithoutAll::class;
    public const DT                 =   Date::class;
    public const DT_MIN             =   DateMin::class;
    public const DT_MAX             =   DateMax::class;
    public const DT_RANGE           =   DateRange::class;
    public const DT_LT              =   DateLessThan::class;
    public const DT_GT              =   DateGreaterThan::class;
    public const STR_MIN            =   StrMin::class;
    public const STR_MAX            =   StrMax::class;
    public const STR_RANGE          =   StrRange::class;
    public const DT_BT              =   DateBetween::class;
    public const STR_BT             =   StrBetween::class;
    public const STR_UP             =   StrUpper::class;
    public const STR_LOW            =   StrLower::class;
    public const ARR_MIN            =   ArrMin::class;
    public const ARR_MAX            =   ArrMax::class;
    public const ARR_LT             =   ArrLessThan::class;
    public const ARR_GT             =   ArrGreaterThan::class;
    public const ARR_RANGE          =   ArrRange::class;
    public const ARR_BT             =   ArrBetween::class;
    public const STR_REGEX          =   StrRegex::class;
    public const STR_FILLED         =   StrFilled::class;
    public const ARR_FILLED         =   ArrFilled::class;
    public const UUID               =   Uuid::class;
    public const ULID               =   Ulid::class;
    public const FILE               =   File::class;
    public const FILE_FILLED        =   FileFilled::class;
    public const FILE_GT            =   FileGreaterThan::class;
    public const FILE_LT            =   FileLessThan::class;
    public const FILE_MAX           =   FileMax::class;
    public const FILE_MIN           =   FileMin::class;
    public const FILE_RANGE         =   FileRange::class;
    public const FILE_BT            =   FileBetween::class;
    public const IP                 =   IpAddress::class;
    public const MAC                =   MacAddress::class;
    public const NUM_BT             =   NumBetween::class;
    public const NUM_GT             =   NumGreaterThan::class;
    public const NUM_LT             =   NumLessThan::class;
    public const NUM_MAX            =   NumMax::class;
    public const NUM_MIN            =   NumMin::class;
    public const NUM_RANGE          =   NumRange::class;
    public const SAME               =   Same::class;
    public const STR_GT             =   StrGreaterThan::class;
    public const STR_LT             =   StrLessThan::class;
    public const STR_SIZE           =   StrSize::class;


    public static function valueOf(string $name): ?string
    {
        $classConstant = static::class . '::' . \strtoupper($name);
        return \defined($classConstant) ? \constant($classConstant) : null;
    }
}
