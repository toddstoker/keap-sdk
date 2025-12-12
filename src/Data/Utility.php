<?php

namespace Toddstoker\KeapSdk\Data;

class Utility
{
    public static function stringToInteger(?string $value): ?int
    {
        if ($value === null) {
            return null;
        }

        return ctype_digit($value) ? (int)$value : null;
    }
}