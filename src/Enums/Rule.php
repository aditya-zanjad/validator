<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\In;
use AdityaZanjad\Validator\Base\Enum;
use AdityaZanjad\Validator\Rules\Max;
use AdityaZanjad\Validator\Rules\Min;
use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Rules\Date;
use AdityaZanjad\Validator\Rules\Mime;
use AdityaZanjad\Validator\Rules\Size;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Equal;
use AdityaZanjad\Validator\Rules\NotIn;
use AdityaZanjad\Validator\Rules\Regex;
use AdityaZanjad\Validator\Rules\Digits;
use AdityaZanjad\Validator\Rules\Filled;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\LessThan;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\TypeFile;
use AdityaZanjad\Validator\Rules\TypeJson;
use AdityaZanjad\Validator\Rules\TypeArray;
use AdityaZanjad\Validator\Rules\LowerCase;
use AdityaZanjad\Validator\Rules\UpperCase;
use AdityaZanjad\Validator\Rules\RequiredIf;
use AdityaZanjad\Validator\Rules\TypeString;
use AdityaZanjad\Validator\Rules\GreaterThan;
use AdityaZanjad\Validator\Rules\LessOrEqual;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use AdityaZanjad\Validator\Rules\TypeInteger;
use AdityaZanjad\Validator\Rules\DateBetween;
use AdityaZanjad\Validator\Rules\DateLessThan;
use AdityaZanjad\Validator\Rules\RequiredWith;
use AdityaZanjad\Validator\Rules\DigitsBetween;
use AdityaZanjad\Validator\Rules\DigitsLessThan;
use AdityaZanjad\Validator\Rules\GreaterOrEqual;
use AdityaZanjad\Validator\Rules\RequiredUnless;
use AdityaZanjad\Validator\Rules\RequiredWithAll;
use AdityaZanjad\Validator\Rules\RequiredWithout;
use AdityaZanjad\Validator\Rules\DateGreaterThan;
use AdityaZanjad\Validator\Rules\DigitsGreaterThan;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;
use AdityaZanjad\Validator\Rules\DateGreaterOrEqual;
use AdityaZanjad\Validator\Rules\DateLessThanOrEqual;
use AdityaZanjad\Validator\Rules\DigitsGreaterOrEqual;
use AdityaZanjad\Validator\Rules\DigitsLessThanOrEqual;

/**
 * @version 1.0
 */
class Rule extends Enum
{
    public const GT                     =   GreaterThan::class;
    public const IN                     =   In::class;
    public const LT                     =   LessThan::class;
    public const GTE                    =   GreaterOrEqual::class;
    public const LTE                    =   LessOrEqual::class;
    public const MAX                    =   Max::class;
    public const MIN                    =   Min::class;
    public const URL                    =   Url::class;
    public const DATE                   =   Date::class;
    public const FILE                   =   TypeFile::class;
    public const JSON                   =   TypeJson::class;
    public const SIZE                   =   Size::class;
    public const ARRAY                  =   TypeArray::class;
    public const EQUAL                  =   Equal::class;
    public const EMAIL                  =   Email::class;
    public const MIMES                  =   Mime::class;
    public const REGEX                  =   Regex::class;
    public const DIGITS                 =   Digits::class;
    public const FILLED                 =   Filled::class;
    public const STRING                 =   TypeString::class;
    public const NOT_IN                 =   NotIn::class;
    public const BOOLEAN                =   TypeBoolean::class;
    public const DATE_GT                =   DateGreaterThan::class;
    public const DATE_LT                =   DateLessThan::class;
    public const INTEGER                =   TypeInteger::class;
    public const NUMERIC                =   Numeric::class;
    public const DATE_GTE               =   DateGreaterOrEqual::class;
    public const DATE_LTE               =   DateLessThanOrEqual::class;
    public const REQUIRED               =   Required::class;
    public const DIGITS_GT              =   DigitsGreaterThan::class;
    public const DIGITS_LT              =   DigitsLessThan::class;
    public const LOWERCASE              =   LowerCase::class;
    public const UPPERCASE              =   UpperCase::class;
    public const DIGITS_GTE             =   DigitsGreaterOrEqual::class;
    public const DIGITS_LTE             =   DigitsLessThanOrEqual::class;
    public const REQUIRED_IF            =   RequiredIf::class;
    public const DATE_BETWEEN           =   DateBetween::class;
    public const REQUIRED_WITH          =   RequiredWith::class;
    public const DIGITS_BETWEEN         =   DigitsBetween::class;
    public const REQUIRED_UNLESS        =   RequiredUnless::class;
    public const REQUIRED_WITHOUT       =   RequiredWithout::class;
    public const REQUIRED_WITH_ALL      =   RequiredWithAll::class;
    public const REQUIRED_WITHOUT_ALL   =   RequiredWithoutAll::class;
}
