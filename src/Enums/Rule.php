<?php

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Required;
use AdityaZanjad\Validator\Rules\Primitives\TypeInt;
use AdityaZanjad\Validator\Rules\Primitives\TypeStr;
use AdityaZanjad\Validator\Rules\Primitives\TypeArr;
use AdityaZanjad\Validator\Rules\Primitives\TypeBool;
use AdityaZanjad\Validator\Rules\Primitives\TypeFloat;

enum Rule: string
{
    case array              =   TypeArr::class;
    case email              =   Email::class;
    case float              =   TypeFloat::class;
    case string             =   TypeStr::class;
    case boolean            =   TypeBool::class;
    case integer            =   TypeInt::class;
    case required           =   Required::class;

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
