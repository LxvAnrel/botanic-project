@if ($paginator->hasPages())
<nav class="flex flex-col sm:flex-row items-center justify-between gap-4" role="navigation">

    {{-- Info --}}
    <p class="text-[10px] uppercase tracking-[0.3em] text-[#3A5E2D] order-2 sm:order-1">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }} espécies
    </p>

    {{-- Controles --}}
    <div class="flex items-center gap-1.5 order-1 sm:order-2">

        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-2 px-5 py-2.5 glass rounded-full text-[10px] uppercase tracking-widest text-[#3A5E2D]/40 cursor-not-allowed select-none">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Anterior
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               wire:navigate
               class="inline-flex items-center gap-2 px-5 py-2.5 glass rounded-full text-[10px] uppercase tracking-widest text-[#7A8E72] hover:text-[#C8A96E] hover:border-[#C8A96E]/40 border border-white/[0.07] transition-all duration-200">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Anterior
            </a>
        @endif

        {{-- Números das páginas --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2.5 text-[10px] text-[#3A5E2D]/50">···</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full text-[11px] font-medium text-[#0E1A0B] bg-[#C8A96E] shadow-[0_0_16px_rgba(200,169,110,0.4)] cursor-default">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           wire:navigate
                           class="inline-flex items-center justify-center w-9 h-9 rounded-full text-[11px] font-medium glass text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5 transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               wire:navigate
               class="inline-flex items-center gap-2 px-5 py-2.5 glass-gold rounded-full text-[10px] uppercase tracking-widest text-[#C8A96E] hover:bg-[#C8A96E]/15 border border-[#C8A96E]/25 transition-all duration-200 shadow-[0_0_20px_rgba(200,169,110,0.15)]">
                Próxima
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="inline-flex items-center gap-2 px-5 py-2.5 glass rounded-full text-[10px] uppercase tracking-widest text-[#3A5E2D]/40 cursor-not-allowed select-none border border-white/[0.05]">
                Próxima
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif

    </div>
</nav>
@endif
