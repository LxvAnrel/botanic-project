@extends('layouts.app')

@section('title', $plant->nome_popular)

@section('content')
<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-10 py-8 md:py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-3 text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-6 md:mb-12">
        <a href="{{ route('plants.index') }}" class="hover:text-[#C8A96E] transition-colors">Catálogo</a>
        <span>—</span>
        <span class="text-[#7A8E72] truncate max-w-[160px]">{{ $plant->nome_popular }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">

        {{-- Imagem --}}
        <div class="relative">
            @if($plant->image_path)
                <img src="{{ asset($plant->image_path) }}"
                     alt="{{ $plant->nome_popular }}"
                     class="w-full aspect-square object-cover rounded-3xl">
            @else
                <div class="w-full aspect-square glass rounded-3xl flex items-center justify-center text-9xl opacity-40">
                    🌿
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="space-y-7">

            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-4">{{ $plant->familia }}</p>
                <h1 class="font-serif font-light text-3xl md:text-5xl text-[#EDE0CC] leading-tight mb-2">
                    {{ $plant->nome_popular }}
                </h1>
                <p class="font-serif italic text-[#7A8E72] text-base md:text-lg">{{ $plant->nome_cientifico }}</p>
            </div>

            {{-- Badges --}}
            <div class="flex flex-wrap gap-2">
                @switch($plant->habitat_luz)
                    @case('sol_pleno')
                        <span class="text-[9px] uppercase tracking-widest glass px-4 py-2 rounded-full text-[#7A8E72]">☀ Sol Pleno</span>
                        @break
                    @case('meia_sombra')
                        <span class="text-[9px] uppercase tracking-widest glass px-4 py-2 rounded-full text-[#7A8E72]">◑ Meia Sombra</span>
                        @break
                    @case('sombra')
                        <span class="text-[9px] uppercase tracking-widest glass px-4 py-2 rounded-full text-[#7A8E72]">● Sombra</span>
                        @break
                @endswitch

                @if($plant->toxica_pets)
                    <span class="text-[9px] uppercase tracking-widest px-4 py-2 rounded-full bg-red-900/20 border border-red-900/30 text-red-400/80">⚠ Tóxica para pets</span>
                @else
                    <span class="text-[9px] uppercase tracking-widest glass-gold px-4 py-2 rounded-full text-[#C8A96E]">🐾 Pet-friendly</span>
                @endif

                <span class="text-[9px] uppercase tracking-widest glass px-4 py-2 rounded-full text-[#7A8E72]">Até {{ $plant->porte_max_cm }}cm</span>
            </div>

            {{-- Ficha --}}
            <div class="glass rounded-2xl divide-y divide-white/[0.05]">
                <div class="grid grid-cols-2 px-6 py-4">
                    <div>
                        <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Gênero</p>
                        <p class="text-[#EDE0CC] text-sm">{{ $plant->genero }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Espécie</p>
                        <p class="text-[#EDE0CC] text-sm italic">{{ $plant->especie }}</p>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Origem</p>
                    <p class="text-[#EDE0CC] text-sm">{{ $plant->origem }}</p>
                </div>
                @if(!empty($plant->epoca_poda))
                <div class="px-6 py-4">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-3">Época de Poda</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($plant->epoca_poda as $season)
                            <span class="text-[9px] uppercase tracking-widest glass-gold text-[#C8A96E] px-3 py-1 rounded-full">{{ ucfirst($season) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Ação --}}
            @auth
                @php $inDiario = auth()->user()->plants()->where('plant_id', $plant->id)->exists(); @endphp
                <button id="btn-favorite"
                        data-active="{{ $inDiario ? '1' : '0' }}"
                        onclick="toggleFavorite({{ $plant->id }})"
                        @class([
                            'w-full text-xs uppercase tracking-widest font-semibold py-4 rounded-full transition-all duration-200 flex items-center justify-center gap-3',
                            'bg-[#C8A96E] hover:bg-[#D4BA8A] text-[#0E1A0B] shadow-[0_0_30px_rgba(200,169,110,0.3)]' => !$inDiario,
                            'glass-gold text-[#C8A96E] hover:text-[#D4BA8A]' => $inDiario,
                        ])>
                    <svg id="btn-favorite-icon" class="w-4 h-4" fill="{{ $inDiario ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span id="btn-favorite-label">{{ $inDiario ? 'No seu Diário Verde' : 'Adicionar ao Diário Verde' }}</span>
                </button>
            @else
                <a href="{{ route('login') }}"
                   class="w-full glass hover:glass-gold text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest font-medium py-4 rounded-full transition-all duration-200 flex items-center justify-center">
                    Faça login para salvar esta planta
                </a>
            @endauth

            {{-- Painel de cuidados (apenas para plantas do Diário) --}}
            @if($care)
            @php
                $corEstado = fn($e) => match($e) {
                    'atrasado' => 'text-red-400',
                    'em_breve' => 'text-[#C8A96E]',
                    'nunca' => 'text-[#7A8E72]',
                    default => 'text-[#3A5E2D]',
                };
            @endphp
            <div class="glass rounded-2xl p-6 space-y-5">
                <div class="flex items-center justify-between">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Cuidados</p>
                    <p class="text-[9px] uppercase tracking-wider text-[#3A5E2D]">Rega a cada {{ $plant->intervaloRega() }}d · Aduba a cada {{ $plant->intervaloAdubacao() }}d</p>
                </div>

                @if(session('care_ok'))
                <div class="text-[#C8A96E] text-xs glass-gold rounded-xl px-4 py-2.5">✓ {{ session('care_ok') }}</div>
                @endif

                {{-- Rega --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">💧 Rega
                            <span class="text-[10px] uppercase tracking-wider {{ $corEstado($care['rega']['estado']) }}">
                                {{ \App\Support\PlantCare::rotulo($care['rega']) }}
                            </span>
                        </p>
                        <p class="text-[#7A8E72] text-[11px] mt-0.5">
                            @if($care['rega']['proxima'])
                                Próxima: {{ $care['rega']['proxima']->format('d/m') }}
                            @else
                                Registre a primeira rega
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('care.store', $plant->id) }}" class="shrink-0">
                        @csrf
                        <input type="hidden" name="tipo" value="rega">
                        <button class="glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200">Reguei hoje</button>
                    </form>
                </div>

                {{-- Adubação --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">🌱 Adubação
                            <span class="text-[10px] uppercase tracking-wider {{ $corEstado($care['adubacao']['estado']) }}">
                                {{ \App\Support\PlantCare::rotulo($care['adubacao']) }}
                            </span>
                        </p>
                        <p class="text-[#7A8E72] text-[11px] mt-0.5">
                            @if($care['adubacao']['proxima'])
                                Próxima: {{ $care['adubacao']['proxima']->format('d/m') }}
                            @else
                                Registre a primeira adubação
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('care.store', $plant->id) }}" class="shrink-0">
                        @csrf
                        <input type="hidden" name="tipo" value="adubacao">
                        <button class="glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200">Adubei</button>
                    </form>
                </div>

                {{-- Poda --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">✂️ Poda</p>
                        <p class="text-[#7A8E72] text-[11px] mt-0.5">
                            @if($care['ultima_poda'])
                                Última: {{ $care['ultima_poda']->format('d/m/Y') }}
                            @else
                                Sem registro de poda
                            @endif
                            @if(!empty($plant->epoca_poda)) · época: {{ implode(', ', array_map('ucfirst', $plant->epoca_poda)) }} @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('care.store', $plant->id) }}" class="shrink-0">
                        @csrf
                        <input type="hidden" name="tipo" value="poda">
                        <button class="glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200">Podei</button>
                    </form>
                </div>

                {{-- Histórico --}}
                @if($care['historico']->count() > 0)
                <div class="border-t border-white/[0.06] pt-4">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-3">Histórico recente</p>
                    <div class="space-y-1.5">
                        @foreach($care['historico'] as $log)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#7A8E72]">
                                {{ \App\Models\CareLog::rotulo($log->tipo) }} · {{ $log->data->format('d/m/Y') }}
                            </span>
                            <form method="POST" action="{{ route('care.destroy', $log->id) }}">
                                @csrf @method('DELETE')
                                <button class="text-[#3A5E2D] hover:text-red-400 transition-colors text-[10px] uppercase tracking-wider">remover</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Detalhes --}}
    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="glass rounded-3xl p-8">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Benefícios</p>
            <p class="text-[#7A8E72] text-sm leading-relaxed">{{ $plant->beneficios }}</p>
        </div>
        <div class="glass rounded-3xl p-8">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Cuidados</p>
            <p class="text-[#7A8E72] text-sm leading-relaxed">{{ $plant->maleficios }}</p>
        </div>
        <div class="glass-gold rounded-3xl p-8">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Curiosidades</p>
            <p class="text-[#7A8E72] text-sm leading-relaxed">{{ $plant->curiosidades }}</p>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast"
     class="fixed bottom-8 right-8 glass-gold text-[#EDE0CC] px-6 py-4 text-xs uppercase tracking-widest rounded-2xl transform translate-y-16 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
    <span id="toast-icon" class="text-[#C8A96E]">✓</span>
    <span id="toast-msg">Adicionada ao Diário Verde</span>
</div>

<script>
let favoriteBusy = false;

function showToast(msg, icon) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    document.getElementById('toast-icon').textContent = icon;
    toast.classList.remove('translate-y-16', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => {
        toast.classList.add('translate-y-16', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3000);
}

function setFavoriteState(active) {
    const btn = document.getElementById('btn-favorite');
    const icon = document.getElementById('btn-favorite-icon');
    const label = document.getElementById('btn-favorite-label');
    btn.dataset.active = active ? '1' : '0';
    label.textContent = active ? 'No seu Diário Verde' : 'Adicionar ao Diário Verde';
    icon.setAttribute('fill', active ? 'currentColor' : 'none');
    const goldSolid = ['bg-[#C8A96E]', 'hover:bg-[#D4BA8A]', 'text-[#0E1A0B]', 'shadow-[0_0_30px_rgba(200,169,110,0.3)]'];
    const goldGhost = ['glass-gold', 'text-[#C8A96E]', 'hover:text-[#D4BA8A]'];
    btn.classList.remove(...(active ? goldSolid : goldGhost));
    btn.classList.add(...(active ? goldGhost : goldSolid));
}

function toggleFavorite(plantId) {
    if (favoriteBusy) return;
    favoriteBusy = true;
    const btn = document.getElementById('btn-favorite');
    btn.classList.add('opacity-60', 'pointer-events-none');

    fetch(`/planta/${plantId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error('request failed');
        return r.json();
    })
    .then(data => {
        setFavoriteState(data.added);
        showToast(data.added ? 'Adicionada ao Diário Verde' : 'Removida do Diário Verde', data.added ? '✓' : '✕');
    })
    .catch(() => showToast('Algo deu errado. Tente novamente.', '!'))
    .finally(() => {
        favoriteBusy = false;
        btn.classList.remove('opacity-60', 'pointer-events-none');
    });
}
</script>
@endsection
