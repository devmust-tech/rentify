<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm shadow-red-600/20 hover:from-red-700 hover:to-red-800 hover:shadow-md hover:shadow-red-600/30 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:from-red-800 active:to-red-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200']) }}>
    {{ $slot }}
</button>
