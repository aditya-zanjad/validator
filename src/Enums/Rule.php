<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\In;
use AdityaZanjad\Validator\Rules\Gt;
use AdityaZanjad\Validator\Rules\Lt;
use AdityaZanjad\Validator\Base\Enum;
use AdityaZanjad\Validator\Rules\Gte;
use AdityaZanjad\Validator\Rules\Lte;
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
use AdityaZanjad\Validator\Rules\DateGt;
use AdityaZanjad\Validator\Rules\DateLt;
use AdityaZanjad\Validator\Rules\Digits;
use AdityaZanjad\Validator\Rules\Filled;
use AdityaZanjad\Validator\Rules\DateGte;
use AdityaZanjad\Validator\Rules\DateLte;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\DigitsGt;
use AdityaZanjad\Validator\Rules\DigitsLt;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\TypeFile;
use AdityaZanjad\Validator\Rules\TypeJson;
use AdityaZanjad\Validator\Rules\DateEqual;
use AdityaZanjad\Validator\Rules\DigitsGte;
use AdityaZanjad\Validator\Rules\DigitsLte;
use AdityaZanjad\Validator\Rules\TypeArray;
use AdityaZanjad\Validator\Rules\LowerCase;
use AdityaZanjad\Validator\Rules\UpperCase;
use AdityaZanjad\Validator\Rules\RequiredIf;
use AdityaZanjad\Validator\Rules\TypeString;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use AdityaZanjad\Validator\Rules\TypeInteger;
use AdityaZanjad\Validator\Rules\DateBetween;
use AdityaZanjad\Validator\Rules\RequiredWith;
use AdityaZanjad\Validator\Rules\DigitsBetween;
use AdityaZanjad\Validator\Rules\RequiredUnless;
use AdityaZanjad\Validator\Rules\RequiredWithAll;
use AdityaZanjad\Validator\Rules\RequiredWithout;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;

/**
 * @version 1.0
 */
class Rule extends Enum
{
    public const EQ                     =   Equal::class;
    public const GT                     =   Gt::class;
    public const IN                     =   In::class;
    public const LT                     =   Lt::class;
    public const GTE                    =   Gte::class;
    public const LTE                    =   Lte::class;
    public const MAX                    =   Max::class;
    public const MIN                    =   Min::class;
    public const URL                    =   Url::class;
    public const DATE                   =   Date::class;
    public const FILE                   =   TypeFile::class;
    public const JSON                   =   TypeJson::class;
    public const SIZE                   =   Size::class;
    public const ARRAY                  =   TypeArray::class;
    public const EMAIL                  =   Email::class;
    public const MIMES                  =   Mime::class;
    public const REGEX                  =   Regex::class;
    public const DIGITS                 =   Digits::class;
    public const FILLED                 =   Filled::class;
    public const STRING                 =   TypeString::class;
    public const NOT_IN                 =   NotIn::class;
    public const BOOLEAN                =   TypeBoolean::class;
    public const DATE_EQ                =   DateEqual::class;
    public const DATE_GT                =   DateGt::class;
    public const DATE_LT                =   DateLt::class;
    public const INTEGER                =   TypeInteger::class;
    public const NUMERIC                =   Numeric::class;
    public const DATE_GTE               =   DateGte::class;
    public const DATE_LTE               =   DateLte::class;
    public const REQUIRED               =   Required::class;
    public const DIGITS_GT              =   DigitsGt::class;
    public const DIGITS_LT              =   DigitsLt::class;
    public const LOWERCASE              =   LowerCase::class;
    public const UPPERCASE              =   UpperCase::class;
    public const DIGITS_GTE             =   DigitsGte::class;
    public const DIGITS_LTE             =   DigitsLte::class;
    public const REQUIRED_IF            =   RequiredIf::class;
    public const DATE_BETWEEN           =   DateBetween::class;
    public const REQUIRED_WITH          =   RequiredWith::class;
    public const DIGITS_BETWEEN         =   DigitsBetween::class;
    public const REQUIRED_UNLESS        =   RequiredUnless::class;
    public const REQUIRED_WITHOUT       =   RequiredWithout::class;
    public const REQUIRED_WITH_ALL      =   RequiredWithAll::class;
    public const REQUIRED_WITHOUT_ALL   =   RequiredWithoutAll::class;
}
