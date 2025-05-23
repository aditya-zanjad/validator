<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\In;
use AdityaZanjad\Validator\Base\Enum;
use AdityaZanjad\Validator\Rules\Max;
use AdityaZanjad\Validator\Rules\Min;
use AdityaZanjad\Validator\Rules\Url;
use AdityaZanjad\Validator\Rules\Mime;
use AdityaZanjad\Validator\Rules\Same;
use AdityaZanjad\Validator\Rules\Size;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Equal;
use AdityaZanjad\Validator\Rules\Regex;
use AdityaZanjad\Validator\Rules\Digits;
use AdityaZanjad\Validator\Rules\Filled;
use AdityaZanjad\Validator\Rules\Numeric;
use AdityaZanjad\Validator\Rules\LessThan;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\TypeFile;
use AdityaZanjad\Validator\Rules\TypeJson;
use AdityaZanjad\Validator\Rules\TypeArray;
use AdityaZanjad\Validator\Rules\TypeFloat;
use AdityaZanjad\Validator\Rules\RequiredIf;
use AdityaZanjad\Validator\Rules\TypeString;
use AdityaZanjad\Validator\Rules\GreaterThan;
use AdityaZanjad\Validator\Rules\TypeBoolean;
use AdityaZanjad\Validator\Rules\TypeInteger;
use AdityaZanjad\Validator\Rules\RequiredWith;
use AdityaZanjad\Validator\Rules\RequiredUnless;
use AdityaZanjad\Validator\Rules\RequiredWithAll;
use AdityaZanjad\Validator\Rules\RequiredWithout;
use AdityaZanjad\Validator\Rules\LessThanOrEqualTo;
use AdityaZanjad\Validator\Rules\RequiredWithoutAll;
use AdityaZanjad\Validator\Rules\GreaterThanOrEqualTo;

/**
 * @version 1.0
 */
class Rule extends Enum
{
    public const GT                     =   GreaterThan::class;
    public const IN                     =   In::class;
    public const LT                     =   LessThan::class;
    public const GTE                    =   GreaterThanOrEqualTo::class;
    public const LTE                    =   LessThanOrEqualTo::class;
    public const MAX                    =   Max::class;
    public const MIN                    =   Min::class;
    public const URL                    =   Url::class;
    public const FILE                   =   TypeFile::class;
    public const JSON                   =   TypeJson::class;
    public const SAME                   =   Same::class;
    public const SIZE                   =   Size::class;
    public const ARRAY                  =   TypeArray::class;
    public const EMAIL                  =   Email::class;
    public const EQUAL                  =   Equal::class;
    public const FLOAT                  =   TypeFloat::class;
    public const MIMES                  =   Mime::class;
    public const REGEX                  =   Regex::class;
    public const DIGITS                 =   Digits::class;
    public const FILLED                 =   Filled::class;
    public const STRING                 =   TypeString::class;
    public const BOOLEAN                =   TypeBoolean::class;
    public const INTEGER                =   TypeInteger::class;
    public const NUMERIC                =   Numeric::class;
    public const REQUIRED               =   Required::class;
    public const REQUIRED_IF            =   RequiredIf::class;
    public const REQUIRED_WITH          =   RequiredWith::class;
    public const REQUIRED_UNLESS        =   RequiredUnless::class;
    public const REQUIRED_WITHOUT       =   RequiredWithout::class;
    public const REQUIRED_WITH_ALL      =   RequiredWithAll::class;
    public const REQUIRED_WITHOUT_ALL   =   RequiredWithoutAll::class;
}
