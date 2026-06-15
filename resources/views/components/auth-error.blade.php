@props(['messages' => [], 'title' => null])

@php $messages = is_array($messages) ? $messages : (array) $messages; @endphp

@if(count($messages))
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-3.5 mb-6 flex gap-3']) }}>
    <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
    </svg>
    <div class="min-w-0">
        @if($title)
            <p class="text-red-300 text-xs font-medium mb-1">{{ $title }}</p>
        @endif
        <ul class="space-y-0.5">
            @foreach($messages as $message)
                <li class="text-red-300/90 text-xs leading-relaxed">{{ $message }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
