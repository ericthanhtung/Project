<?php

namespace App\Constants;

class Status
{
    public const UN_PUBLIC = 0;
    public const PUBLIC = 1;

    public static function all():array {
        return [
            self::UN_PUBLIC,
            self::PUBLIC,
        ];
    }
}
