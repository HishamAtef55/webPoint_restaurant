<?php

namespace App\Enums;

enum MaterialType: string
{
    case MANUFACTURED_MATERIAL = 'خامة مصنعة';
    case DAILY_INVENTORY = 'جرد يومى';
    case PACKAGE = 'باكدج';
    case All_GROUPS = 'جميع المجموعات';

    public function toString(): string
    {
        return match ($this) {
            self::MANUFACTURED_MATERIAL => 'خامة مصنعة',
            self::DAILY_INVENTORY => 'جرد يومى',
            self::PACKAGE => 'باكدج',
            self::All_GROUPS => 'جميع المجموعات'
        };
    }

    public static function values(): array
    {
        return [
            self::MANUFACTURED_MATERIAL,
            self::DAILY_INVENTORY,
            self::PACKAGE,
            self::All_GROUPS,
        ];
    }
}
