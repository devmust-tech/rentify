@props(['name' => 'county', 'selected' => null])

<select name="{{ $name }}" {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm transition']) }}>
    <option value="">Select County...</option>
    @foreach(config('counties.counties') as $code => $county)
        <option value="{{ $code }}" {{ old($name, $selected) == $code ? 'selected' : '' }}>
            {{ $county }}
        </option>
    @endforeach
</select>
