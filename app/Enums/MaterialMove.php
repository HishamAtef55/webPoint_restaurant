<?php

namespace App\Enums;

enum MaterialMove: string
{
    case PURCHASES = 'purchases';

    public function toString(): string
    {
        return match ($this) {
            self::PURCHASES => 'مشتريات',
        };
    }
}
