<?php

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\TypeArr;
use AdityaZanjad\Validator\Rules\TypeInt;
use AdityaZanjad\Validator\Rules\TypeStr;
use AdityaZanjad\Validator\Rules\TypeBool;
use AdityaZanjad\Validator\Rules\TypeFloat;
use AdityaZanjad\Validator\Rules\Required\Required;
use AdityaZanjad\Validator\Rules\Required\RequiredIf;
use AdityaZanjad\Validator\Rules\Required\RequiredWith;
use AdityaZanjad\Validator\Rules\Required\RequiredWithout;

enum Rule: string
{
    case array              =   TypeArr::class;
    case email              =   Email::class;
    case float              =   TypeFloat::class;
    case string             =   TypeStr::class;
    case boolean            =   TypeBool::class;
    case integer            =   TypeInt::class;
    case required           =   Required::class;
    case required_if        =   RequiredIf::class;
    case required_with      =   RequiredWith::class;
    case required_without   =   RequiredWithout::class;


    /**
     * Try to fetch value of the case by the given name.
     *
     * @param string $name
     * 
     * @return string
     */
    public static function tryFromName(string $name): null|string
    {
        if (!defined("self::{$name}")) {
            return null;
        }

        return constant("self::{$name}")->value;
    }
}
