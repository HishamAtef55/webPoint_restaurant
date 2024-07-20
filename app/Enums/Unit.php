<?php

namespace App\Enums;


enum Unit: string
{

    case KILO = 'كيلو';
    case LITRE = 'لتر';
    case NUMBER = 'عدد';

    public function toString(): string
    {
        return match ($this) {
            self::KILO => 'كيلو',
            self::LITRE => 'لتر',
            self::NUMBER => 'عدد'
        };
    }

    public static function values()
    {
        return [
            Unit::KILO,
            Unit::LITRE,
            Unit::NUMBER,
        ];
    }
}
