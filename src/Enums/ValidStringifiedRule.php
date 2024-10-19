<?php

namespace AdityaZanjad\Validator\Enums;

use AdityaZanjad\Validator\Rules\Email;
use AdityaZanjad\Validator\Rules\Comparators\In;
use AdityaZanjad\Validator\Rules\Comparators\Max;
use AdityaZanjad\Validator\Rules\Comparators\Min;
use AdityaZanjad\Validator\Rules\Comparators\Size;
use AdityaZanjad\Validator\Rules\Primitives\TypeArr;
use AdityaZanjad\Validator\Rules\Primitives\TypeInt;
use AdityaZanjad\Validator\Rules\Primitives\TypeNum;
use AdityaZanjad\Validator\Rules\Primitives\TypeStr;
use AdityaZanjad\Validator\Rules\Primitives\TypeBool;
use AdityaZanjad\Validator\Rules\Constraints\Required;
use AdityaZanjad\Validator\Rules\Constraints\RequiredIf;
use AdityaZanjad\Validator\Rules\Constraints\RequiredWith;
use AdityaZanjad\Validator\Rules\Constraints\RequiredUnless;
use AdityaZanjad\Validator\Rules\Constraints\RequiredWithout;

enum ValidStringifiedRule: string
{
    case in                 =   In::class;
    case max                =   Max::class;
    case min                =   Min::class;
    case size               =   Size::class;
    case array              =   TypeArr::class;
    case email              =   Email::class;
    case numeric            =   TypeNum::class;
    case string             =   TypeStr::class;
    case boolean            =   TypeBool::class;
    case integer            =   TypeInt::class;
    case required           =   Required::class;
    case required_if        =   RequiredIf::class;
    case required_with      =   RequiredWith::class;
    case required_unless    =   RequiredUnless::class;
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

    /**
     * Get the names of the cases that point to the constraint rules.
     *
     * @return array<int, string>
     */
    public static function getConstraintRulesCases(): array
    {
        return ['required'];
    }
}
