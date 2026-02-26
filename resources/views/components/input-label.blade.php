@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 tracking-tight']) }}>
    {{ $value ?? $slot }}
</label>
