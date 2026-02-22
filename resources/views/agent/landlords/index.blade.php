<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Landlords</h2>
            <a href="{{ route('agent.landlords.create') }}"
               class="inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Landlord
            </a>
        </div>
    </x-slot>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Phone</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Properties</th>
                    <th class="bg-gray-50/50 px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($landlords as $landlord)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('agent.landlords.show', $landlord) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                                {{ $landlord->user->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $landlord->user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $landlord->user->phone ?? '--' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">
                                {{ $landlord->properties->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="inline-flex items-center gap-x-3">
                                <a href="{{ route('agent.landlords.show', $landlord) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">View</a>
                                <a href="{{ route('agent.landlords.edit', $landlord) }}"
                                   class="font-medium text-gray-600 hover:text-gray-500 transition-colors">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-gray-900">No landlords yet</p>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding your first landlord.</p>
                                <a href="{{ route('agent.landlords.create') }}"
                                   class="mt-4 inline-flex items-center gap-x-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Add Landlord
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($landlords->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $landlords->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
