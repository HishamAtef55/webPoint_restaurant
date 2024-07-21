<?php

namespace App\Enums;

enum MaterialType: string
{
    case MANUFACTURED_MATERIAL = 'manufactured_material';
    case DAILY_INVENTORY = 'daily_inventory';
    case PACKAGE = 'package';
    case All_GROUPS = 'all_groups';

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

    public static function view($materialValue): array
    {
        $material = self::tryFrom($materialValue);

        if (!$material) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name' => '',
                'value' => '',
            ];
        }

        return [
            'name' => $material->toString(),
            'value' => $material->value,
        ];
    }
}
