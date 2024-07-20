<?php

namespace App\Enums;

enum MaterialType: string
{
    case MANUFACTURED_MATERIAL = "manufactured_material";
    case DAILY_INVENTORY =  "daily_inventory";
    case Package = "package";
    case All_GROUPS = "all_group";

    public function toString(): string
    {
        return match ($this) {
            self::MANUFACTURED_MATERIAL => 'خامة مصنعة',
            self::DAILY_INVENTORY => 'جرد يومى',
            self::All_GROUPS => 'جميع المجموعات',
            self::Package => 'باكدج'
        };
    }

    public static function values(): array
    {
        return [
            self::MANUFACTURED_MATERIAL,
            self::DAILY_INVENTORY,
            self::All_GROUPS,
            self::Package,
        ];
    }
}
