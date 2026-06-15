@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-xs text-[#C8A96E] border border-[#C8A96E]/20 bg-[#C8A96E]/5 px-4 py-3 mb-4']) }}>
        {{ $status }}
    </div>
@endif
