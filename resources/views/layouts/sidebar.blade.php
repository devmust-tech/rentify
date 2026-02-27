<div class="flex flex-1 flex-col gap-y-4 py-4">
    {{-- Role nav links --}}
    <div class="flex-1 space-y-1 overflow-y-auto">
        @if(auth()->user()->isAgent())
            @include('components.sidebar.agent')
        @elseif(auth()->user()->isLandlord())
            @include('components.sidebar.landlord')
        @elseif(auth()->user()->isTenant())
            @include('components.sidebar.tenant')
        @endif
    </div>

    {{-- User profile block (bottom of sidebar) --}}
    <div class="border-t border-white/[0.06] pt-4 mt-auto">
        <div class="flex items-center gap-x-3 rounded-xl bg-white/5 px-3 py-2.5 ring-1 ring-white/10 hover:bg-white/8 transition group">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 font-bold text-sm text-white shadow-lg shadow-indigo-500/30">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                <p class="truncate text-[11px] text-gray-500 mt-0.5">{{ ucfirst(strtolower(Auth::user()->role->value)) }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="shrink-0 rounded-lg p-1.5 text-gray-500 hover:text-white hover:bg-white/10 transition" title="Profile Settings">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </a>
        </div>
    </div>
</div>
