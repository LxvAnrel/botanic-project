<div class="space-y-8">

    {{-- Filtros --}}
    <div class="space-y-4">
        {{-- Search --}}
        <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#3A5E2D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nome, família botânica..."
                class="w-full pl-11 pr-4 py-3.5 glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm rounded-full focus:outline-none focus:border-[#C8A96E]/50 transition-all duration-200"
            >
        </div>

        {{-- Pills --}}
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mr-2">Filtrar:</span>

            <button wire:click="$set('habitat', '')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ !$habitat ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                Todas
            </button>
            <button wire:click="$set('habitat', 'sol_pleno')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $habitat === 'sol_pleno' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ☀ Sol Pleno
            </button>
            <button wire:click="$set('habitat', 'meia_sombra')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $habitat === 'meia_sombra' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ◑ Meia Sombra
            </button>
            <button wire:click="$set('habitat', 'sombra')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $habitat === 'sombra' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ● Sombra
            </button>
            <button wire:click="$set('petFriendly', !$petFriendly)"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $petFriendly ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                🐾 Pet-friendly
            </button>

            <span class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mx-1">Porte:</span>
            <button wire:click="$set('size', 'pequeno')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $size === 'pequeno' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ◻ Pequeno
            </button>
            <button wire:click="$set('size', 'medio')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $size === 'medio' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ◼ Médio
            </button>
            <button wire:click="$set('size', 'grande')"
                    class="text-xs uppercase tracking-wider px-5 py-2 rounded-full border transition-all duration-200 {{ $size === 'grande' ? 'pill-active' : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/40 hover:text-[#C8A96E]' }}">
                ▣ Grande
            </button>

            @if($search || $habitat || $petFriendly || $size)
            <button wire:click="$set('search', ''); $set('habitat', ''); $set('petFriendly', false); $set('size', '');"
                    class="text-xs text-[#7A8E72] hover:text-[#C8A96E] ml-2 transition-colors underline underline-offset-4">
                Limpar
            </button>
            @endif
        </div>
    </div>

    {{-- Contagem --}}
    @if($plants->count() > 0)
    <p class="text-[#3A5E2D] text-xs uppercase tracking-widest">{{ $plants->total() }} espécie{{ $plants->total() !== 1 ? 's' : '' }}</p>
    @endif

    {{-- Grid de cards --}}
    @if($plants->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($plants as $planta)
            <a href="{{ route('plants.show', $planta) }}" class="group relative overflow-hidden block glass rounded-3xl">

                {{-- Imagem ou placeholder --}}
                @if($planta->image_path)
                    <img src="{{ asset('storage/' . $planta->image_path) }}"
                         alt="{{ $planta->nome_popular }}"
                         class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                @else
                    @php
                        $bgs = [
                            'bg-gradient-to-br from-[#1A3A1A] to-[#0D2010]',
                            'bg-gradient-to-br from-[#1E3520] to-[#112015]',
                            'bg-gradient-to-br from-[#15301A] to-[#0A1D0D]',
                            'bg-gradient-to-br from-[#1A3525] to-[#0D2018]',
                        ];
                        $plant_icons = ['🌿','🪴','🌱','🍃','🌾','🌳'];
                    @endphp
                    <div class="w-full h-72 {{ $bgs[$loop->index % 4] }} flex items-center justify-center relative">
                        <span class="text-6xl opacity-30 group-hover:opacity-60 group-hover:scale-110 transition-all duration-500">
                            {{ $plant_icons[$loop->index % 6] }}
                        </span>
                    </div>
                @endif

                {{-- Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1408]/95 via-[#0A1408]/20 to-transparent rounded-3xl"></div>

                {{-- Badge topo --}}
                <div class="absolute top-4 left-4 flex gap-2">
                    @if(!$planta->toxica_pets)
                        <span class="text-[9px] uppercase tracking-wider bg-[#C8A96E]/15 text-[#C8A96E] border border-[#C8A96E]/25 px-3 py-1 rounded-full backdrop-blur-sm">Pet-safe</span>
                    @endif
                </div>

                {{-- Info fundo --}}
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="font-serif text-xl text-[#EDE0CC] leading-tight group-hover:text-[#C8A96E] transition-colors duration-300">
                                {{ $planta->nome_popular }}
                            </p>
                            <p class="text-[#7A8E72] text-xs italic mt-0.5">{{ $planta->nome_cientifico }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-1.5">
                            <span class="text-[9px] uppercase tracking-wider text-[#7A8E72]">
                                @switch($planta->habitat_luz)
                                    @case('sol_pleno') ☀ Sol @break
                                    @case('meia_sombra') ◑ Meia @break
                                    @case('sombra') ● Sombra @break
                                @endswitch
                            </span>
                            <span class="text-[9px] uppercase tracking-wider text-[#7A8E72]">{{ $planta->porte_max_cm }}cm</span>
                        </div>
                    </div>
                </div>

                {{-- Seta hover --}}
                <div class="absolute top-4 right-4 w-8 h-8 glass rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <svg class="w-3.5 h-3.5 text-[#C8A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>

            </a>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-white/[0.06]">
            {{ $plants->links() }}
        </div>

    @else
        <div class="glass rounded-3xl p-20 text-center">
            <p class="font-serif text-4xl text-[#2D4A23] mb-4">∅</p>
            <p class="text-[#7A8E72] text-sm mb-6">Nenhuma espécie encontrada com esses critérios.</p>
            <button wire:click="$set('search', ''); $set('habitat', ''); $set('petFriendly', false);"
                    class="text-xs uppercase tracking-widest text-[#C8A96E] glass-gold px-6 py-2.5 rounded-full hover:text-[#D4BA8A] transition-all duration-200">
                Limpar filtros
            </button>
        </div>
    @endif
</div>
