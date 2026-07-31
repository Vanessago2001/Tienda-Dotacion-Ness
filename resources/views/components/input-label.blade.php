@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#40E0D0]']) }}>
    {{ $value ?? $slot }}
</label>
