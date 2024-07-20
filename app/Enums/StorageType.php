<?php

namespace App\Enums;


enum StorageType: string
{

    case FREEZE = 'freeze';
    case COOLING = 'cooling';
    case FLOOR = 'floor';
    case SHELVES = 'shelves';
    case OHTER = 'other';

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

    public static function view($storageType): string
    {
        return match ($storageType) {
            self::FREEZE => 'تجميد',
            self::COOLING => 'تبريد',
            self::FLOOR => 'أرضية',
            self::SHELVES => 'أرفف',
            self::OHTER => 'اخرى'
        };
    }
}
