@extends('layouts.app')

@section('title', 'Meu Diário Verde')

@section('content')

{{-- Sub-nav do dashboard --}}
<div class="sticky top-[72px] z-40 px-4 pb-2">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-2xl px-2 py-1.5 flex gap-1 overflow-x-auto">
            <a href="{{ route('dashboard') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
                Diário
            </a>
            <a href="{{ route('alertas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Alertas
            </a>
            <a href="{{ route('conquistas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Conquistas
            </a>
            <a href="{{ route('perfil') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Perfil
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-10 py-8">

    {{-- Header --}}
    @php
        $userXp      = (int) auth()->user()->xp;
        $userStreak  = (int) auth()->user()->streak_days;
        $lvlProgress = \App\Support\Gamification::levelProgress($userXp);
        $lvl         = $lvlProgress['level'];
    @endphp
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 pb-6 border-b border-white/[0.06]">
        <div>
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">— Bem-vindo de volta</p>
            <h1 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC]">{{ auth()->user()->name }}</h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="text-[10px] uppercase tracking-wider text-[#C8A96E]">{{ $lvl['icon'] }} {{ $lvl['label'] }}</span>
                <span class="text-[#3A5E2D] text-[10px]">{{ $userXp }} XP</span>
                @if($userStreak > 0)
                <span class="text-[10px] text-amber-400/80">🔥 {{ $userStreak }}d</span>
                @endif
            </div>
        </div>
        <a href="{{ route('plants.index') }}"
           class="self-start sm:self-auto glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-5 py-2.5 rounded-full transition-all duration-200 whitespace-nowrap">
            + Adicionar plantas
        </a>
    </div>

    {{-- XP bar --}}
    @if($lvlProgress['nextLevel'])
    <div class="glass rounded-2xl px-5 py-3 mb-6 flex items-center gap-4">
        <span class="text-[9px] uppercase tracking-wider text-[#7A8E72] shrink-0">{{ $lvl['label'] }}</span>
        <div class="flex-1 bg-white/[0.06] rounded-full h-1.5">
            <div class="bg-[#C8A96E] h-1.5 rounded-full transition-all duration-700"
                 style="width: {{ $lvlProgress['percent'] }}%"></div>
        </div>
        <a href="{{ route('conquistas') }}" class="text-[9px] uppercase tracking-wider text-[#7A8E72] hover:text-[#C8A96E] shrink-0 transition-colors">
            {{ $lvlProgress['nextLevel']['label'] }} →
        </a>
    </div>
    @endif

    {{-- Banner de notificações push --}}
    <div id="push-banner" class="glass-gold rounded-2xl p-4 md:p-5 mb-6 flex items-center gap-4" style="display:none;">
        <div class="shrink-0 w-10 h-10 glass rounded-full flex items-center justify-center text-lg">🔔</div>
        <div class="flex-1 min-w-0">
            <p class="text-[#EDE0CC] text-sm">Receba alertas de poda no seu celular</p>
            <p class="text-[#7A8E72] text-xs mt-0.5">Avisamos na hora certa de cuidar de cada planta.</p>
        </div>
        <button type="button" onclick="floraBannerEnable()"
                class="shrink-0 bg-[#C8A96E] hover:bg-[#D4BA8A] text-[#0E1A0B] text-[10px] uppercase tracking-widest font-semibold px-4 py-2.5 rounded-full transition-all duration-200">
            Ativar
        </button>
        <button type="button" onclick="floraBannerDismiss()" aria-label="Dispensar"
                class="shrink-0 text-[#7A8E72] hover:text-[#C8A96E] transition-colors text-lg leading-none px-1">×</button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="glass rounded-2xl p-4 md:p-6 text-center">
            <p class="font-serif text-2xl md:text-3xl text-[#C8A96E]" style="text-shadow:0 0 20px rgba(200,169,110,0.4)">
                {{ auth()->user()->plants()->count() }}
            </p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Plantas</p>
        </div>
        <div class="glass rounded-2xl p-4 md:p-6 text-center">
            <p class="font-serif text-2xl md:text-3xl text-[#C8A96E]" style="text-shadow:0 0 20px rgba(200,169,110,0.4)">
                {{ auth()->user()->notifications()->count() }}
            </p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Alertas</p>
        </div>
        <div class="glass rounded-2xl p-4 md:p-6 text-center">
            @php
                $seasons = ['verão'=>'Verão','outono'=>'Outono','inverno'=>'Inverno','primavera'=>'Primavera'];
                $month = now()->month;
                $currentSeason = match(true) {
                    in_array($month, [12,1,2]) => 'verão',
                    in_array($month, [3,4,5])  => 'outono',
                    in_array($month, [6,7,8])  => 'inverno',
                    default                    => 'primavera',
                };
            @endphp
            <p class="font-serif text-lg md:text-xl text-[#C8A96E] mt-1">{{ $seasons[$currentSeason] }}</p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Estação</p>
        </div>
    </div>

    @if($plantas->count() > 0)

        {{-- Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            @foreach($plantas as $planta)
            <a href="{{ route('plants.show', $planta) }}" class="group relative overflow-hidden block glass rounded-2xl">

                @if($planta->image_path)
                    <img src="{{ asset($planta->image_path) }}"
                         alt="{{ $planta->nome_popular }}"
                         class="w-full h-40 md:h-52 object-cover group-hover:scale-105 transition-transform duration-700 opacity-75 group-hover:opacity-100">
                @else
                    @php
                        $bgs = ['from-[#1A3A1A] to-[#0D2010]','from-[#1E3520] to-[#112015]','from-[#15301A] to-[#0A1D0D]','from-[#1A3525] to-[#0D2018]'];
                        $icons = ['🌿','🪴','🌱','🍃','🌾','🌳'];
                    @endphp
                    <div class="w-full h-40 md:h-52 bg-gradient-to-br {{ $bgs[$loop->index % 4] }} flex items-center justify-center text-4xl opacity-25 group-hover:opacity-40 transition-opacity">
                        {{ $icons[$loop->index % 6] }}
                    </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1408]/95 via-[#0A1408]/10 to-transparent rounded-2xl"></div>

                {{-- Pet badge --}}
                @if(!$planta->toxica_pets)
                    <div class="absolute top-2 left-2">
                        <span class="text-[8px] uppercase tracking-wider bg-[#C8A96E]/15 text-[#C8A96E] border border-[#C8A96E]/25 px-2 py-0.5 rounded-full backdrop-blur-sm">Pet</span>
                    </div>
                @endif

                {{-- Selo de rega --}}
                @php $rs = $regaStatus[$planta->id] ?? null; @endphp
                @if($rs)
                    @php
                        $regaCls = match($rs['estado']) {
                            'atrasado' => 'bg-red-500/20 text-red-300 border-red-400/30',
                            'em_breve' => 'bg-[#C8A96E]/20 text-[#C8A96E] border-[#C8A96E]/30',
                            'nunca' => 'bg-white/10 text-[#7A8E72] border-white/15',
                            default => 'bg-[#1A3A1A]/40 text-[#7A8E72] border-white/10',
                        };
                    @endphp
                    <div class="absolute top-2 right-2">
                        <span class="text-[8px] uppercase tracking-wider {{ $regaCls }} border px-2 py-0.5 rounded-full backdrop-blur-sm">
                            💧 {{ \App\Support\PlantCare::rotulo($rs) }}
                        </span>
                    </div>
                @endif

                <div class="absolute bottom-0 left-0 right-0 p-3">
                    <p class="font-serif text-sm text-[#EDE0CC] leading-tight group-hover:text-[#C8A96E] transition-colors duration-200">{{ $planta->nome_popular }}</p>
                    <p class="text-[#3A5E2D] text-[10px] mt-0.5">{{ $planta->pivot->created_at->diffForHumans() }}</p>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Paginação --}}
        @if($plantas->hasPages())
        <div class="mt-8 pt-6 border-t border-white/[0.06]">
            {{ $plantas->links() }}
        </div>
        @endif

    @else
        <div class="glass rounded-3xl p-16 md:p-24 text-center">
            <div class="w-20 h-20 glass-gold rounded-full flex items-center justify-center mx-auto mb-6 text-4xl">🌱</div>
            <p class="font-serif font-light text-xl md:text-2xl text-[#EDE0CC] mb-2">Seu diário está vazio</p>
            <p class="text-[#7A8E72] text-sm mb-8 max-w-xs mx-auto">Explore o catálogo e salve as plantas que você quer cultivar.</p>
            <a href="{{ route('plants.index') }}"
               class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-8 py-4 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_30px_rgba(200,169,110,0.3)]">
                Explorar catálogo
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    @endif
</div>

{{-- Modal de onboarding: pergunta sobre notificações logo após o cadastro --}}
@if(session('ask_push'))
<div id="push-onboarding" class="fixed inset-0 z-[60] flex items-center justify-center px-4" style="display:none;">
    <div class="absolute inset-0 bg-[#0A1408]/80 backdrop-blur-sm" onclick="floraOnboardingClose()"></div>
    <div class="relative glass rounded-3xl p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 glass-gold rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">🔔</div>
        <h2 class="font-serif font-light text-2xl text-[#EDE0CC] mb-2">Quer receber alertas?</h2>
        <p class="text-[#7A8E72] text-sm mb-7 leading-relaxed">
            Podemos avisar no seu celular quando for a época ideal de poda das suas plantas. Você pode mudar isso depois no perfil.
        </p>
        <div class="space-y-2">
            <button type="button" onclick="floraOnboardingEnable()"
                    class="w-full bg-[#C8A96E] hover:bg-[#D4BA8A] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold py-3.5 rounded-full transition-all duration-200">
                Ativar agora
            </button>
            <button type="button" onclick="floraOnboardingClose()"
                    class="w-full text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest py-3 rounded-full transition-colors">
                Agora não
            </button>
        </div>
    </div>
</div>
@endif

<script>
const FLORA_PUSH_DISMISS = 'flora_push_dismissed';

function floraBannerDismiss() {
    localStorage.setItem(FLORA_PUSH_DISMISS, '1');
    var b = document.getElementById('push-banner');
    if (b) b.style.display = 'none';
}

async function floraBannerEnable() {
    if (!window.Flora || !window.Flora.push) return;
    var ok = await window.Flora.push.subscribe();
    if (ok) floraBannerDismiss();
}

function floraOnboardingClose() {
    var m = document.getElementById('push-onboarding');
    if (m) m.style.display = 'none';
}

async function floraOnboardingEnable() {
    floraOnboardingClose();
    if (window.Flora && window.Flora.push) {
        var ok = await window.Flora.push.subscribe();
        if (ok) localStorage.setItem(FLORA_PUSH_DISMISS, '1');
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    var supported = window.Flora && window.Flora.push && window.Flora.push.supported();
    var onboarding = document.getElementById('push-onboarding');

    // Mostra o modal de onboarding (pós-cadastro) se push é suportado e ainda não foi decidido.
    if (onboarding && supported && Notification.permission === 'default') {
        onboarding.style.display = 'flex';
        return; // não mostra o banner junto com o modal
    }

    // Banner persistente para quem ainda não ativou nem dispensou.
    if (supported && Notification.permission !== 'denied' && !localStorage.getItem(FLORA_PUSH_DISMISS)) {
        var on = await window.Flora.push.isSubscribed();
        if (!on) {
            var b = document.getElementById('push-banner');
            if (b) b.style.display = 'flex';
        }
    }
});
</script>
@endsection
