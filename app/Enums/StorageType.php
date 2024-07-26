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

    public static function view($storageType): array
    {
        $storage = self::tryFrom($storageType);

        if (!$storage) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name_ar' => '',
                'name_en' => '',
            ];
        }

        return [
            'name_ar' => $storage->toString(),
            'name_en' => $storage->value,
        ];
    }
}
