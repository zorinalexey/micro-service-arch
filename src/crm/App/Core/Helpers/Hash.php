<?php

namespace App\Core\Helpers;

final class Hash
{
    public static function make ($string): string
    {
        return hash('sha256', md5(base64_encode($string)));
    }
}