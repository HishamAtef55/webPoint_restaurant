<?php

namespace App\Enums;


enum StorageType: string
{

    case FREEZE = 'تجميد';
    case COOLING = 'تبريد';
    case FLOOR = 'أرضية';
    case SHELVES = 'أرفف';
    case OHTER = 'اخرى';

    public function toString(): string
    {
        return match ($this) {
            self::FREEZE => 'تجميد',
            self::COOLING => 'تبريد',
            self::FLOOR => 'أرضية',
            self::SHELVES => 'أرفف',
            self::OHTER => 'اخرى'
        };
    }

    public static function values()
    {
        return [
            StorageType::FREEZE,
            StorageType::COOLING,
            StorageType::FLOOR,
            StorageType::SHELVES,
            StorageType::OHTER,
        ];
    }
}
