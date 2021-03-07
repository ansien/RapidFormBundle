<?php

declare(strict_types=1);

namespace Ansien\RapidFormBundle\Util;

class NamingUtils
{
    public static function classToSnake(object $inputObject): string
    {
        $parts = explode('\\', $inputObject::class);
        $class = end($parts);

        return NamingUtils::stringToSnake($class);
    }

    public static function stringToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
