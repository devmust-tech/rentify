<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Maintenance Request Details</h2>
            <a href="{{ route('tenant.maintenance.index') }}" class="rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">{{ $maintenance->title }}</h3>
                <div class="flex items-center gap-x-2">
                    <x-status-badge :status="$maintenance->priority" />
                    <x-status-badge :status="$maintenance->status" />
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$maintenance->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1"><x-status-badge :status="$maintenance->priority" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->created_at->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->property->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $maintenance->unit->unit_number }}</dd>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $maintenance->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($maintenance->photos && count($maintenance->photos))
        <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Photos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($maintenance->photos as $photo)
                        <a href="{{ Storage::url($photo) }}" target="_blank" class="group relative overflow-hidden rounded-lg ring-1 ring-gray-200 hover:ring-indigo-300 transition-all">
                            <img src="{{ Storage::url($photo) }}" alt="Maintenance photo" class="h-40 w-full object-cover group-hover:scale-105 transition-transform duration-200">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
