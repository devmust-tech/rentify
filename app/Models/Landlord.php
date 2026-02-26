<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'national_id',
        'payment_details',
    ];

    protected function casts(): array
    {
        return [
            'payment_details' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function agreements()
    {
        return $this->hasMany(AgentAgreement::class);
    }
}
