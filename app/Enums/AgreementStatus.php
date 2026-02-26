<?php

namespace App\Enums;

enum AgreementStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::ACTIVE => 'Active',
            self::EXPIRED => 'Expired',
            self::TERMINATED => 'Terminated',
        };
    }
}
