@props(['value'])
<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-400']) }}>
    {{ $value ?? $slot }}
</label>
