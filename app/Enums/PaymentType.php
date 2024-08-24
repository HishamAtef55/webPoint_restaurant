<?php

namespace App\Enums;

enum PaymentType: string
{
    case INSTALLMENT = 'installment';
    case CASH = 'cash';

    public function toString(): string
    {
        return match ($this) {
            self::CASH => 'نقدى',
            self::INSTALLMENT => 'اجل',
        };
    }

    public static function values(): array
    {
        return [
            self::CASH,
            self::INSTALLMENT,
        ];
    }

    public static function view($paymentType): array
    {
        $payment = self::tryFrom($paymentType);

        if (!$payment) {
            // Handle the case where the unit value is invalid or not found
            return [
                'name_ar' => '',
                'name_en' => '',
            ];
        }

        return [
            'name_ar' => $payment->toString(),
            'name_en' => $payment->value,
        ];
    }
}
