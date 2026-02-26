<?php

namespace App\Models;

use App\Enums\MaintenancePriority;
use App\Enums\MaintenanceStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'unit_id',
        'tenant_id',
        'title',
        'description',
        'photos',
        'priority',
        'status',
        'assigned_to',
        'resolution_notes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'priority' => MaintenancePriority::class,
            'status' => MaintenanceStatus::class,
            'resolved_at' => 'datetime',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function notes()
    {
        return $this->hasMany(MaintenanceNote::class);
    }
}
