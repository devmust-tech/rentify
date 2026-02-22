<nav class="flex flex-1 flex-col px-4 py-4 space-y-1 overflow-y-auto">
    @if(auth()->user()->isAgent())
        @include('components.sidebar.agent')
    @elseif(auth()->user()->isLandlord())
        @include('components.sidebar.landlord')
    @elseif(auth()->user()->isTenant())
        @include('components.sidebar.tenant')
    @endif
</nav>
