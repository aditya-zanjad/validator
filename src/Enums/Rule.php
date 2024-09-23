<?php

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Comparators\In;
use AdityaZanjad\Validator\Rules\Comparators\Max;
use AdityaZanjad\Validator\Rules\Comparators\Min;
use AdityaZanjad\Validator\Rules\Comparators\Size;
use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\Primitives\TypeInt;
use AdityaZanjad\Validator\Rules\Primitives\TypeStr;
use AdityaZanjad\Validator\Rules\Primitives\TypeArr;
use AdityaZanjad\Validator\Rules\Primitives\TypeNum;
use AdityaZanjad\Validator\Rules\Primitives\TypeBool;


enum Rule: string
{
    case in         =   In::class;
    case max        =   Max::class;
    case min        =   Min::class;
    case size       =   Size::class;
    case array      =   TypeArr::class;
    case email      =   Email::class;
    case numeric    =   TypeNum::class;
    case string     =   TypeStr::class;
    case boolean    =   TypeBool::class;
    case integer    =   TypeInt::class;
    case required   =   Required::class;

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
