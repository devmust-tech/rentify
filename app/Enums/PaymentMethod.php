<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case MPESA = 'mpesa';
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CHEQUE = 'cheque';

    public function label(): string
    {
        return match ($this) {
            self::MPESA => 'M-Pesa',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CASH => 'Cash',
            self::CHEQUE => 'Cheque',
        };
    }
}
