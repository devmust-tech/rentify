<x-admin-layout>
    <x-slot name="title">{{ $organization->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.organizations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">← Organizations</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $organization->name }}</h1>
        </div>
        <div class="flex items-center gap-3">
            @if($organization->status->value === 'pending')
                <form method="POST" action="{{ route('admin.organizations.approve', $organization) }}">
                    @csrf
                    <button class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500 transition">Approve Workspace</button>
                </form>
            @elseif($organization->status->value === 'active')
                <form method="POST" action="{{ route('admin.organizations.suspend', $organization) }}">
                    @csrf
                    <button class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">Suspend</button>
                </form>
            @elseif($organization->status->value === 'suspended')
                <form method="POST" action="{{ route('admin.organizations.approve', $organization) }}">
                    @csrf
                    <button class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500 transition">Reactivate</button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.organizations.destroy', $organization) }}"
                @confirm-delete-org.window="$el.submit()">
                @csrf
                @method('DELETE')
                <button type="button" @click="$dispatch('open-modal', 'delete-org')"
                    class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition">Delete</button>
            </form>
            <x-confirm-modal name="delete-org" title="Delete Organization"
                :message="'This will permanently delete the organization, all its users, properties, leases, and data. This cannot be undone.'"
                confirmLabel="Delete Organization" />
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 ring-1 ring-emerald-200">{{ session('success') }}</div>
    @endif

    {{-- KPI Metrics Row --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $metrics['properties'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Properties</p>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $metrics['units'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Units</p>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $metrics['active_leases'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Active Leases</p>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $metrics['active_tenants'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Active Tenants</p>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-base font-bold text-gray-900">{{ number_format($metrics['total_revenue'], 0) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Revenue (KSh)</p>
        </div>
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 px-4 py-4 text-center">
            <p class="text-2xl font-bold {{ $metrics['pending_maintenance'] > 0 ? 'text-amber-600' : 'text-gray-400' }}">{{ $metrics['pending_maintenance'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Open Requests</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Details card -->
        <div class="lg:col-span-2 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Details</h2>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Name</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $organization->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$organization->status" /></dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Subdomain</dt>
                    <dd class="mt-1 text-sm font-mono text-gray-700">{{ $organization->slug }}.{{ config('app.domain') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Owner</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $organization->owner?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Users</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $organization->users_count }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Created</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $organization->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Branding card -->
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Branding</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: {{ $organization->primary_color }}"></div>
                    <div>
                        <p class="text-xs text-gray-500">Primary Color</p>
                        <p class="text-sm font-mono text-gray-700">{{ $organization->primary_color }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg" style="background-color: {{ $organization->accent_color }}"></div>
                    <div>
                        <p class="text-xs text-gray-500">Accent Color</p>
                        <p class="text-sm font-mono text-gray-700">{{ $organization->accent_color }}</p>
                    </div>
                </div>
                @if($organization->logo)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Logo</p>
                        <img src="{{ Storage::url($organization->logo) }}" alt="Logo" class="h-12 w-auto rounded-lg ring-1 ring-gray-200">
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Billing / Subscription card --}}
    @php
        $subStatus = $organization->subscription_status;
        $subColors = [
            'active'   => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'trialing' => 'bg-blue-50 text-blue-700 ring-blue-200',
            'past_due' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'canceled' => 'bg-red-50 text-red-700 ring-red-200',
        ];
        $subColor = $subColors[$subStatus ?? ''] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
        $subLabel = $organization->isOnTrial() ? 'Trial' : ucfirst(str_replace('_', ' ', $subStatus ?? 'none'));
    @endphp

    <div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Billing &amp; Subscription</h2>
        <dl class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-4">
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Plan</dt>
                <dd class="mt-1 text-sm font-bold text-indigo-600 capitalize">{{ $organization->plan ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Subscription Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $subColor }}">{{ $subLabel }}</span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Trial Ends</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">
                    @if($organization->trial_ends_at)
                        {{ $organization->trial_ends_at->format('d M Y') }}
                        @if($organization->isOnTrial())
                            <span class="text-xs text-amber-600">({{ $organization->trialDaysLeft() }}d left)</span>
                        @else
                            <span class="text-xs text-gray-400">(expired)</span>
                        @endif
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Active Features</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ count($organization->features ?? []) }}</dd>
            </div>
            @if($organization->stripe_customer_id)
            <div class="col-span-2">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stripe Customer</dt>
                <dd class="mt-1 font-mono text-xs text-gray-600 bg-gray-50 rounded px-2 py-1 inline-block">{{ $organization->stripe_customer_id }}</dd>
            </div>
            @endif
            @if($organization->stripe_subscription_id)
            <div class="col-span-2">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stripe Subscription</dt>
                <dd class="mt-1 font-mono text-xs text-gray-600 bg-gray-50 rounded px-2 py-1 inline-block">{{ $organization->stripe_subscription_id }}</dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- Plan & Features card --}}
    @php
        $allFeatures = [
            'properties'    => 'Properties',
            'units'         => 'Units',
            'tenants'       => 'Tenants',
            'leases'        => 'Leases',
            'maintenance'   => 'Maintenance',
            'notifications' => 'Notifications',
            'invoices'      => 'Invoices',
            'payments'      => 'Payments',
            'financials'    => 'Financials',
            'reports'       => 'Reports',
            'agreements'    => 'Agreements',
        ];
        $currentPlan     = $organization->plan ?? 'pro';
        $currentFeatures = $organization->features ?? array_keys($allFeatures);
    @endphp

    <div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6"
         x-data="{
             plan: '{{ $currentPlan }}',
             features: {{ json_encode($currentFeatures) }},
             planFeatures: {
                 basic:      ['properties','units','tenants','leases','maintenance','notifications'],
                 pro:        ['properties','units','tenants','leases','maintenance','notifications','invoices','payments','financials'],
                 enterprise: ['properties','units','tenants','leases','maintenance','notifications','invoices','payments','financials','reports','agreements']
             },
             selectPlan(p) {
                 this.plan = p;
                 this.features = [...this.planFeatures[p]];
             },
             hasFeature(f) {
                 return this.features.includes(f);
             },
             toggleFeature(f) {
                 if (this.hasFeature(f)) {
                     this.features = this.features.filter(x => x !== f);
                 } else {
                     this.features.push(f);
                 }
             }
         }">
        <h2 class="text-base font-semibold text-gray-900 mb-5">Plan &amp; Features</h2>

        <form method="POST" action="{{ route('admin.organizations.plan', $organization) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="plan" :value="plan">

            {{-- Plan selector --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                @foreach(['basic' => ['Basic', 'text-gray-500', 'Core property management'], 'pro' => ['Pro', 'text-indigo-500', 'Finance & billing included'], 'enterprise' => ['Enterprise', 'text-purple-500', 'All features unlocked']] as $p => [$label, $color, $desc])
                    <button type="button" @click="selectPlan('{{ $p }}')"
                        :class="plan === '{{ $p }}'
                            ? 'border-indigo-500 bg-indigo-50 ring-2 ring-indigo-500/20'
                            : 'border-gray-200 bg-white hover:border-gray-300'"
                        class="flex flex-col items-start rounded-xl border-2 p-4 text-left transition-all duration-150 cursor-pointer">
                        <span class="text-sm font-bold {{ $color }}" :class="plan === '{{ $p }}' ? 'text-indigo-700' : ''">{{ $label }}</span>
                        <span class="text-xs text-gray-400 mt-0.5">{{ $desc }}</span>
                        <div x-show="plan === '{{ $p }}'" class="mt-2">
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Active</span>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Feature toggles --}}
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Active Features</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($allFeatures as $key => $label)
                        <label class="flex items-center gap-2.5 rounded-lg border border-gray-200 px-3 py-2.5 cursor-pointer transition-colors"
                               :class="hasFeature('{{ $key }}') ? 'bg-indigo-50 border-indigo-200' : 'bg-white hover:bg-gray-50'">
                            <input type="checkbox"
                                   name="features[]"
                                   value="{{ $key }}"
                                   x-model="features"
                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium"
                                  :class="hasFeature('{{ $key }}') ? 'text-indigo-900' : 'text-gray-600'">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                Save Plan
            </button>
        </form>
    </div>

    {{-- Activity Log section --}}
    <div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Audit Log (last 30 events)</h2>
        @if($activityLogs->isEmpty())
            <p class="text-sm text-gray-500">No activity recorded yet.</p>
        @else
            <div class="divide-y divide-gray-100 text-sm">
                @foreach($activityLogs as $log)
                    <div class="py-2.5 flex items-start gap-3">
                        <span class="mt-0.5 inline-block rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-mono text-indigo-600 shrink-0">{{ $log->action }}</span>
                        <div class="min-w-0">
                            <p class="text-gray-700">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->user?->name ?? 'System' }} &middot; {{ $log->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Users section --}}
    <div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Users ({{ $users->count() }})</h2>

        @if($users->isEmpty())
            <p class="text-sm text-gray-500">No users found for this organization.</p>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($users as $user)
                    <div class="flex items-center justify-between py-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-4 shrink-0">
                            <form method="POST"
                                  action="{{ route('admin.organizations.users.role', [$organization, $user]) }}"
                                  class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role"
                                    class="rounded-lg border border-gray-300 bg-white py-1.5 pl-3 pr-8 text-xs font-medium text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option value="agent"    {{ $user->role->value === 'agent'    ? 'selected' : '' }}>Agent</option>
                                    <option value="landlord" {{ $user->role->value === 'landlord' ? 'selected' : '' }}>Landlord</option>
                                    <option value="tenant"   {{ $user->role->value === 'tenant'   ? 'selected' : '' }}>Tenant</option>
                                </select>
                                <button type="submit"
                                    class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                                    Update
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.organizations.impersonate', [$organization, $user]) }}"
                                  onsubmit="return confirm('Impersonate {{ addslashes($user->name) }}?')">
                                @csrf
                                <button type="submit"
                                    class="rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 hover:bg-amber-200 transition">
                                    Impersonate
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
