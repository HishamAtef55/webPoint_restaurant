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

    public static function view($unitValue): array
    {
        $unit = self::tryFrom($unitValue);

        if (!$unit) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name' => '',
                'value' => '',
            ];
        }

        return [
            'name' => $unit->toString(),
            'value' => $unit->value,
        ];
    }
}
