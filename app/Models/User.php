<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUlids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    public function landlord()
    {
        return $this->hasOne(Landlord::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function agentAgreements()
    {
        return $this->hasMany(AgentAgreement::class, 'agent_id');
    }

    public function isAgent(): bool
    {
        return $this->role === UserRole::AGENT;
    }

    public function isLandlord(): bool
    {
        return $this->role === UserRole::LANDLORD;
    }

    public function isTenant(): bool
    {
        return $this->role === UserRole::TENANT;
    }
}
