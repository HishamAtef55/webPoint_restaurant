<?php

namespace App\Enums;

enum MaterialMove: string
{
    case PURCHASES = 'purchases';
    case EXCHANGE = 'exchange';

    public function toString(): string
    {
        return match ($this) {
            self::PURCHASES => 'مشتريات',
            self::EXCHANGE => 'صرف',
        };
    }
}
