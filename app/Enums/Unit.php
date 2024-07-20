<?php

namespace App\Enums;


enum Unit: string
{

    case KILO = 'kilo';
    case LITRE = 'litre';
    case NUMBER = 'number';

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

    public static function view(Unit $unit): string
    {
        return match ($unit) {
            self::KILO => 'كيلو',
            self::LITRE => 'لتر',
            self::NUMBER => 'عدد'
        };
    }
}
