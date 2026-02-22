<?php

namespace App\Enums;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case COMMERCIAL = 'commercial';
    case LAND = 'land';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
