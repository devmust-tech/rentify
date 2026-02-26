<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm shadow-indigo-600/20 hover:from-indigo-700 hover:to-indigo-800 hover:shadow-md hover:shadow-indigo-600/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:from-indigo-800 active:to-indigo-900 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200']) }}>
    {{ $slot }}
</button>
