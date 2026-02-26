<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'category',
        'icon',
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_amenity')
            ->withPivot('included_in_rent', 'provider', 'monthly_cost', 'notes')
            ->withTimestamps();
    }
}
