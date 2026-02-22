@props(['status'])

@php
    $colorMap = [
        // Invoice
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'partial' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'overdue' => 'bg-red-50 text-red-700 ring-red-600/20',
        // Lease
        'draft' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'pending_signature' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'expired' => 'bg-red-50 text-red-700 ring-red-600/20',
        'terminated' => 'bg-red-50 text-red-700 ring-red-600/20',
        // Unit
        'vacant' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'occupied' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'maintenance' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        // Payment
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'confirmed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'failed' => 'bg-red-50 text-red-700 ring-red-600/20',
        'reversed' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'refunded' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        // Invoice extra
        'cancelled' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'partially_paid' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        // Maintenance (pending/completed/cancelled already mapped above)
        'in_progress' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        // Priority
        'low' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'medium' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'high' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
        'urgent' => 'bg-red-50 text-red-700 ring-red-600/20',
        // User
        'inactive' => 'bg-gray-50 text-gray-700 ring-gray-600/20',
        'suspended' => 'bg-red-50 text-red-700 ring-red-600/20',
        // Property types
        'apartment' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'house' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'commercial' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
        'office' => 'bg-cyan-50 text-cyan-700 ring-cyan-600/20',
        'warehouse' => 'bg-slate-50 text-slate-700 ring-slate-600/20',
        'land' => 'bg-lime-50 text-lime-700 ring-lime-600/20',
        // Payment methods
        'mpesa' => 'bg-green-50 text-green-700 ring-green-600/20',
        'bank_transfer' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'cash' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'cheque' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
    ];

    $statusValue = $status instanceof \BackedEnum ? $status->value : (string) $status;
    $colorClass = $colorMap[$statusValue] ?? 'bg-gray-50 text-gray-700 ring-gray-600/20';
    $label = str_replace('_', ' ', ucfirst($statusValue));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {$colorClass}"]) }}>
    {{ $label }}
</span>
