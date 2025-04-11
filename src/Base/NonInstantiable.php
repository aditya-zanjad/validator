<?php

declare(strict_types=1);

namespace AdityaZanjad\Validator\Base;

use Exception;

/**
 * @version 1.0
 */
class NonInstantiable
{
    /**
     * Make this class & its child classes completely non-instantiable.
     */
    final public function __construct()
    {
        throw new Exception("[Developer][Exception]: The class [" . static::class . "] is a non-instantiable one.");
    }
}
