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
    case ARRAY              =   TypeArr::class;
    case EMAIL              =   Email::class;
    case FLOAT              =   TypeFloat::class;
    case STRING             =   TypeStr::class;
    case BOOLEAN            =   TypeBool::class;
    case INTEGER            =   TypeInt::class;
    case REQUIRED           =   Required::class;
    case REQUIRED_IF        =   RequiredIf::class;
    case REQUIRED_WITH      =   RequiredWith::class;
    case REQUIRED_WITHOUT   =   RequiredWithout::class;


    /**
     * Try to fetch value of the case by the given name.
     *
     * @param string $name
     * 
     * @return string
     */
    public static function tryFromName(string $name): string
    {
        if (!defined("self::{$name}")) {
            return null;
        }

        return constant("self::{$name}")->value;
    }
}
