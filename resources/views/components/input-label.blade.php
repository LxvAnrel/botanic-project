@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mb-2']) }}>
    {{ $value ?? $slot }}
</label>
