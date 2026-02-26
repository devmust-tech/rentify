<?php

namespace App\Models;

use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'landlord_id',
        'agent_id',
        'name',
        'address',
        'county',
        'property_type',
        'description',
        'photos',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'property_type' => PropertyType::class,
        ];
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity')
            ->withPivot('included_in_rent', 'provider', 'monthly_cost', 'notes')
            ->withTimestamps();
    }

    public function getVacantUnitsCountAttribute(): int
    {
        return $this->units()->where('status', 'vacant')->count();
    }

    public function getOccupiedUnitsCountAttribute(): int
    {
        return $this->units()->where('status', 'occupied')->count();
    }

    public function getTotalUnitsCountAttribute(): int
    {
        return $this->units()->count();
    }

    public function getCountyNameAttribute(): string
    {
        return config('counties.counties')[$this->county] ?? $this->county;
    }
}
