<?php

namespace App\Models;

use App\Enums\OrganizationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'primary_color',
        'accent_color',
        'status',
        'owner_id',
        'settings',
        'plan',
        'features',
        'stripe_customer_id',
        'stripe_subscription_id',
        'subscription_status',
        'trial_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => OrganizationStatus::class,
            'settings'     => 'array',
            'features'     => 'array',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    public function subscriptionIsActive(): bool
    {
        return in_array($this->subscription_status, ['active', 'trialing'])
            || $this->isOnTrial();
    }

    public function trialDaysLeft(): int
    {
        if (!$this->isOnTrial()) return 0;
        return (int) now()->diffInDays($this->trial_ends_at, false);
    }

    public function hasFeature(string $feature): bool
    {
        if ($this->features === null) return true;
        return in_array($feature, $this->features);
    }

    public static function planFeatures(string $plan): array
    {
        return match ($plan) {
            'basic'      => ['properties', 'units', 'tenants', 'leases', 'maintenance', 'notifications'],
            'enterprise' => ['properties', 'units', 'tenants', 'leases', 'maintenance', 'notifications',
                             'invoices', 'payments', 'reports', 'agreements', 'financials'],
            default      => ['properties', 'units', 'tenants', 'leases', 'maintenance', 'notifications',
                             'invoices', 'payments', 'financials'],   // pro
        };
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function subdomainUrl(): string
    {
        return 'http://'.$this->slug.'.'.config('app.domain');
    }
}
