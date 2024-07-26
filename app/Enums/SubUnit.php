<?php

namespace App\Enums;


enum SubUnit: string
{

    case GM = 'gm';
    case ML = 'ml';
    case NUMBER = 'number';

    public function toString(): string
    {
        return match ($this) {
            self::GM => 'جرام',
            self::ML => 'مللى',
            self::NUMBER => 'عدد'
        };
    }

    public static function values()
    {
        return [
            SubUnit::GM,
            SubUnit::ML,
            SubUnit::NUMBER,
        ];
    }

    public static function view($subUnitValue): array
    {
        $subUnit = self::tryFrom($subUnitValue);

        if (!$subUnit) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name_ar' => '',
                'name_en' => '',
            ];
        }

        return [
            'name_ar' => $subUnit->toString(),
            'name_en' => $subUnit->value,
            'value' => $subUnit->value === 'number' ? 1 : 1000,
        ];
    }
}
