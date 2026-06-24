@extends('layouts.app')

@section('title', $plant->nome_popular)

@section('content')
<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-10 py-8 md:py-10">

    {{-- Navegação de volta --}}
    <div class="flex items-center gap-3 mb-6 md:mb-12">
        <a href="{{ route('plants.index') }}"
           class="flex items-center gap-2 glass border border-white/[0.08] hover:border-[#C8A96E]/40
                  text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase tracking-widest
                  px-4 py-2 rounded-full transition-all duration-200 group shrink-0">
            <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Catálogo
        </a>
        <span class="text-white/10">—</span>
        <span class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72]/60 truncate">{{ $plant->nome_popular }}</span>
    </div>

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
                        <span class="text-[9px] uppercase tracking-widest bg-[#1A2E17] border border-white/10 px-4 py-2 rounded-full text-[#9AA88E]">☀ Sol Pleno</span>
                        @break
                    @case('meia_sombra')
                        <span class="text-[9px] uppercase tracking-widest bg-[#1A2E17] border border-white/10 px-4 py-2 rounded-full text-[#9AA88E]">◑ Meia Sombra</span>
                        @break
                    @case('sombra')
                        <span class="text-[9px] uppercase tracking-widest bg-[#1A2E17] border border-white/10 px-4 py-2 rounded-full text-[#9AA88E]">● Sombra</span>
                        @break
                @endswitch

                @if($plant->toxica_pets)
                    <span class="text-[9px] uppercase tracking-widest px-4 py-2 rounded-full bg-red-950 border border-red-900/40 text-red-400">⚠ Tóxica para pets</span>
                @else
                    <span class="text-[9px] uppercase tracking-widest bg-[#C8A96E]/15 border border-[#C8A96E]/30 px-4 py-2 rounded-full text-[#C8A96E]">🐾 Pet-friendly</span>
                @endif

                <span class="text-[9px] uppercase tracking-widest bg-[#1A2E17] border border-white/10 px-4 py-2 rounded-full text-[#9AA88E]">Até {{ $plant->porte_max_cm }}cm</span>
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
            <div id="care-panel" class="glass rounded-2xl p-6 space-y-5" style="{{ $inDiario ? '' : 'display:none;' }}">
                <div class="flex items-center justify-between">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Cuidados</p>
                    <p class="text-[9px] uppercase tracking-wider text-[#3A5E2D]">Rega a cada {{ $plant->intervaloRega() }}d · Aduba a cada {{ $plant->intervaloAdubacao() }}d</p>
                </div>

                {{-- Rega --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">💧 Rega
                            <span id="care-status-rega" class="text-[10px] uppercase tracking-wider {{ $corEstado($care['rega']['estado']) }}">
                                {{ \App\Support\PlantCare::rotulo($care['rega']) }}
                            </span>
                        </p>
                        <p id="care-next-rega" class="text-[#7A8E72] text-[11px] mt-0.5">
                            {{ $care['rega']['proxima'] ? 'Próxima: ' . $care['rega']['proxima']->format('d/m') : 'Registre a primeira rega' }}
                        </p>
                    </div>
                    <button type="button" onclick="careAction(this, {{ $plant->id }}, 'rega')"
                            class="shrink-0 glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200 disabled:opacity-50">Reguei hoje</button>
                </div>

                {{-- Adubação --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">🌱 Adubação
                            <span id="care-status-adubacao" class="text-[10px] uppercase tracking-wider {{ $corEstado($care['adubacao']['estado']) }}">
                                {{ \App\Support\PlantCare::rotulo($care['adubacao']) }}
                            </span>
                        </p>
                        <p id="care-next-adubacao" class="text-[#7A8E72] text-[11px] mt-0.5">
                            {{ $care['adubacao']['proxima'] ? 'Próxima: ' . $care['adubacao']['proxima']->format('d/m') : 'Registre a primeira adubação' }}
                        </p>
                    </div>
                    <button type="button" onclick="careAction(this, {{ $plant->id }}, 'adubacao')"
                            class="shrink-0 glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200 disabled:opacity-50">Adubei</button>
                </div>

                {{-- Poda --}}
                <div class="flex items-center justify-between gap-3 border-t border-white/[0.06] pt-4">
                    <div class="min-w-0">
                        <p class="text-[#EDE0CC] text-sm flex items-center gap-2">✂️ Poda</p>
                        <p class="text-[#7A8E72] text-[11px] mt-0.5">
                            <span id="care-last-poda">{{ $care['ultima_poda'] ? 'Última: ' . $care['ultima_poda']->format('d/m/Y') : 'Sem registro de poda' }}</span>@if(!empty($plant->epoca_poda)) · época: {{ implode(', ', array_map('ucfirst', $plant->epoca_poda)) }}@endif
                        </p>
                    </div>
                    <button type="button" onclick="careAction(this, {{ $plant->id }}, 'poda')"
                            class="shrink-0 glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200 disabled:opacity-50">Podei</button>
                </div>

                {{-- Histórico --}}
                <div id="care-historico-wrap" class="border-t border-white/[0.06] pt-4" style="{{ $care['historico']->count() ? '' : 'display:none;' }}">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-3">Histórico recente</p>
                    <div id="care-historico" class="space-y-1.5">
                        @foreach($care['historico'] as $log)
                        <div class="flex items-center justify-between text-xs" data-log="{{ $log->id }}">
                            <span class="text-[#7A8E72]">{{ \App\Models\CareLog::rotulo($log->tipo) }} · {{ $log->data->format('d/m/Y') }}</span>
                            <button type="button" onclick="careRemove(this, {{ $log->id }})"
                                    class="text-[#3A5E2D] hover:text-red-400 transition-colors text-[10px] uppercase tracking-wider disabled:opacity-50">remover</button>
                        </div>
                        @endforeach
                    </div>
                </div>
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

    {{-- Recomendações ──────────────────────────────────────────────────────── --}}
    @if($relacionadas->isNotEmpty())
    <div class="mt-24">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">— Você também pode gostar</p>
                <h2 class="font-serif font-light text-2xl md:text-3xl text-[#EDE0CC]">Plantas <em class="text-[#C8A96E]">relacionadas</em></h2>
            </div>
            <a href="{{ route('plants.index') }}"
               class="shrink-0 text-[9px] uppercase tracking-widest text-[#7A8E72] hover:text-[#C8A96E] transition-colors">
                Ver catálogo →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relacionadas as $rel)
            <a href="{{ route('plants.show', $rel) }}"
               class="group glass rounded-2xl overflow-hidden hover:glass-gold transition-all duration-300">

                {{-- Imagem --}}
                @if($rel->image_path)
                    <img src="{{ asset($rel->image_path) }}" alt="{{ $rel->nome_popular }}"
                         class="w-full h-40 object-cover opacity-70 group-hover:opacity-90 transition-opacity duration-300">
                @else
                    <div class="w-full h-40 bg-gradient-to-br from-[#1A3A1A] to-[#0D2010] flex items-center justify-center text-5xl opacity-20">
                        🌿
                    </div>
                @endif

                {{-- Info --}}
                <div class="p-4 space-y-1">
                    <p class="text-[#EDE0CC] text-sm font-medium leading-snug group-hover:text-[#C8A96E] transition-colors">
                        {{ $rel->nome_popular }}
                    </p>
                    <p class="text-[#7A8E72] text-[10px] italic truncate">{{ $rel->nome_cientifico }}</p>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="text-[8px] uppercase tracking-wider text-[#3A5E2D]">
                            {{ ['sol_pleno' => '☀ Sol', 'meia_sombra' => '◑ Meia', 'sombra' => '● Sombra'][$rel->habitat_luz] ?? '' }}
                        </span>
                        @if($rel->toxica_pets)
                            <span class="text-[8px] text-red-400/60">⚠</span>
                        @else
                            <span class="text-[8px] text-[#C8A96E]/50">🐾</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

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

    // Mostra/esconde o painel de cuidados sem recarregar a pagina.
    const panel = document.getElementById('care-panel');
    if (panel) panel.style.display = active ? '' : 'none';
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

/* ── Cuidados (sem reload) ───────────────────────────────────────── */
const FLORA_CSRF = document.querySelector('meta[name="csrf-token"]').content;

function careColor(estado) {
    return ({ atrasado: 'text-red-400', em_breve: 'text-[#C8A96E]', nunca: 'text-[#7A8E72]' })[estado] || 'text-[#3A5E2D]';
}

function renderCareTipo(tipo, s) {
    const status = document.getElementById('care-status-' + tipo);
    const next = document.getElementById('care-next-' + tipo);
    if (status) {
        status.textContent = s.rotulo;
        status.className = 'text-[10px] uppercase tracking-wider ' + careColor(s.estado);
    }
    if (next) {
        next.textContent = s.proxima
            ? 'Próxima: ' + s.proxima
            : (tipo === 'rega' ? 'Registre a primeira rega' : 'Registre a primeira adubação');
    }
}

function renderHistorico(list) {
    const wrap = document.getElementById('care-historico');
    const section = document.getElementById('care-historico-wrap');
    if (!wrap) return;
    wrap.innerHTML = list.map(l =>
        '<div class="flex items-center justify-between text-xs" data-log="' + l.id + '">' +
            '<span class="text-[#7A8E72]">' + l.label + ' · ' + l.data + '</span>' +
            '<button type="button" onclick="careRemove(this, ' + l.id + ')" class="text-[#3A5E2D] hover:text-red-400 transition-colors text-[10px] uppercase tracking-wider disabled:opacity-50">remover</button>' +
        '</div>'
    ).join('');
    if (section) section.style.display = list.length ? '' : 'none';
}

function renderCare(data) {
    renderCareTipo('rega', data.rega);
    renderCareTipo('adubacao', data.adubacao);
    const poda = document.getElementById('care-last-poda');
    if (poda) poda.textContent = data.ultima_poda ? 'Última: ' + data.ultima_poda : 'Sem registro de poda';
    renderHistorico(data.historico);
    if (data.message) showToast(data.message, '✓');
}

async function careAction(btn, plantId, tipo) {
    if (btn.disabled) return;
    btn.disabled = true;
    const original = btn.textContent;
    btn.textContent = '…';
    try {
        const r = await fetch('/planta/' + plantId + '/cuidado', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': FLORA_CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ tipo }),
        });
        if (!r.ok) throw new Error('falhou');
        renderCare(await r.json());
    } catch (e) {
        showToast('Algo deu errado. Tente novamente.', '!');
    } finally {
        btn.disabled = false;
        btn.textContent = original;
    }
}

async function careRemove(btn, logId) {
    if (btn.disabled) return;
    btn.disabled = true;
    try {
        const r = await fetch('/cuidado/' + logId, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': FLORA_CSRF, 'Accept': 'application/json' },
        });
        if (!r.ok) throw new Error('falhou');
        renderCare(await r.json());
    } catch (e) {
        showToast('Não foi possível remover.', '!');
        btn.disabled = false;
    }
}
</script>
@endsection
