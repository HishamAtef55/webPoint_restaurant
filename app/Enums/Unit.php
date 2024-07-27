<?php

namespace App\Enums;


enum Unit: string
{

    case KILO = 'kilo';
    case LITRE = 'litre';
    case NUMBER = 'number';
    case GM = 'gm';
    case ML = 'ml';

    public function toString(): string
    {
        return match ($this) {
            self::KILO => 'كيلو',
            self::LITRE => 'لتر',
            self::GM => 'جرام',
            self::ML => 'مللى',
            self::NUMBER => 'عدد',
            default => '',
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

    public static function view($unitValue): array
    {
        $unit = self::tryFrom($unitValue);

        if (!$unit) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name_ar' => '',
                'name_en' => '',
                'sub_unit' => [],
            ];
        }

        return [
            'name_ar' => $unit->toString(),
            'name_en' => $unit->value,
            'sub_unit' => self::getSubUnit($unit),
        ];
    }

    private static function getSubUnit(Unit $unit): array
    {
        return match ($unit) {
            self::KILO => [
                'name_ar' => self::GM->toString(),
                'name_en' => self::GM->value,
                'value' => 1000,
            ],
            self::LITRE => [
                'name_ar' => self::ML->toString(),
                'name_en' => self::ML->value,
                'value' => 1000,
            ],
            self::NUMBER => [
                'name_ar' => self::NUMBER->toString(),
                'name_en' => self::NUMBER->value,
                'value' => 1,
            ],
            default => '',
        };
    }
}
