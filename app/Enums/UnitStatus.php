<?php

namespace App\Enums;

enum UnitStatus: string
{
    case VACANT = 'vacant';
    case OCCUPIED = 'occupied';
    case MAINTENANCE = 'maintenance';
    case RESERVED = 'reserved';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
