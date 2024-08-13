<?php

namespace App\Enums;

enum PurchasesMethod: string
{
    case SECTIONS = 'sections';
    case STORES = 'stores';

    public function toString(): string
    {
        return match ($this) {
            self::SECTIONS => 'أقسام',
            self::STORES => 'مخازن',
        };
    }

    public static function values(): array
    {
        return [
            self::SECTIONS,
            self::STORES,
        ];
    }

    public static function view($type): array
    {
        $purchases = self::tryFrom($type);

        if (!$purchases) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name_ar' => '',
                'name_en' => '',
            ];
        }

        return [
            'name_ar' => $purchases->toString(),
            'name_en' => $purchases->value,
        ];
    }
}
