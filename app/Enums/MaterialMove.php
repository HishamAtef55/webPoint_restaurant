<?php

namespace App\Enums;

enum MaterialMove: string
{
    case PURCHASES = 'purchases';
    case EXCHANGE = 'exchange';
    case TRANSFER = 'transfer';
    case HALK = 'halk';

    public function toString(): string
    {
        return match ($this) {
            self::PURCHASES => 'مشتريات',
            self::EXCHANGE => 'صرف',
            self::TRANSFER => 'تحويل',
            self::HALK => 'هالك',
        };
    }
}
