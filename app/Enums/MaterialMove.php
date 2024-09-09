<?php

namespace App\Enums;

enum MaterialMove: string
{
    case PURCHASES = 'purchases';
    case EXCHANGE = 'exchange';
    case TRANSFER = 'transfer';
    case HALK = 'halk';
    case HALKITEM = 'halk_item';
    case SUPPLIER_REFUND = 'supplier_refund';

    public function toString(): string
    {
        return match ($this) {
            self::PURCHASES => 'مشتريات',
            self::EXCHANGE => 'صرف',
            self::TRANSFER => 'تحويل',
            self::HALK => 'هالك',
            self::HALKITEM => 'هالك صنف',
            self::SUPPLIER_REFUND => 'مرتجع الى مورد',
        };
    }
}
