<?php

namespace App\Enums;

enum OrganizationStatus: string
{
    case PENDING   = 'pending';
    case ACTIVE    = 'active';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::PENDING   => 'Pending Approval',
            self::ACTIVE    => 'Active',
            self::SUSPENDED => 'Suspended',
        };
    }
}
