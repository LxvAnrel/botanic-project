@extends('layouts.app')

@section('title', 'Conquistas')

@section('content')

{{-- Sub-nav --}}
<div class="sticky top-[72px] z-40 px-4 pb-2">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-2xl px-2 py-1.5 flex gap-1 overflow-x-auto">
            <a href="{{ route('dashboard') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Diário
            </a>
            <a href="{{ route('alertas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Alertas
            </a>
            <a href="{{ route('conquistas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
                Conquistas
            </a>
            <a href="{{ route('perfil') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Perfil
            </a>
        </div>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 md:px-6 py-8 space-y-8">

    {{-- Hero: nível + XP + streak --}}
    @php
        $user    = auth()->user();
        $level   = $progress['level'];
        $next    = $progress['nextLevel'] ?? null;
        $percent = $progress['percent'];
        $streak  = (int) $user->streak_days;
        $earnedCount = collect($badges)->where('earned', true)->count();
    @endphp

    <div class="glass-gold rounded-3xl p-6 md:p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            {{-- Ícone do nível --}}
            <div class="shrink-0 w-20 h-20 glass rounded-full flex items-center justify-center text-4xl"
                 style="box-shadow: 0 0 40px rgba(200,169,110,0.2);">
                {{ $level['icon'] }}
            </div>

            <div class="flex-1 text-center sm:text-left">
                <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-1">Seu nível</p>
                <h2 class="font-serif text-3xl text-[#C8A96E] mb-1">{{ $level['label'] }}</h2>
                <p class="text-[#EDE0CC] text-sm mb-4">{{ $user->xp }} XP total</p>

                {{-- Barra de progresso --}}
                @if($next)
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] uppercase tracking-wider text-[#7A8E72]">{{ $progress['inLevel'] }} / {{ $progress['needed'] }} XP para {{ $next['label'] }} {{ $next['icon'] }}</span>
                        <span class="text-[9px] text-[#C8A96E]">{{ $percent }}%</span>
                    </div>
                    <div class="w-full bg-white/[0.06] rounded-full h-1.5">
                        <div class="bg-[#C8A96E] h-1.5 rounded-full transition-all duration-700"
                             style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @else
                <p class="text-[10px] uppercase tracking-wider text-[#C8A96E]">Nível máximo atingido 🏆</p>
                @endif
            </div>
        </div>

        {{-- Stats rápidos --}}
        <div class="grid grid-cols-3 gap-3 mt-6 pt-6 border-t border-white/[0.06]">
            <div class="text-center">
                <p class="font-serif text-2xl text-[#C8A96E]">{{ $streak }}</p>
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#7A8E72] mt-0.5">Dias seguidos</p>
            </div>
            <div class="text-center border-x border-white/[0.06]">
                <p class="font-serif text-2xl text-[#C8A96E]">{{ $earnedCount }}</p>
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#7A8E72] mt-0.5">Badges ganhos</p>
            </div>
            <div class="text-center">
                <p class="font-serif text-2xl text-[#C8A96E]">{{ $user->plants()->count() }}</p>
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#7A8E72] mt-0.5">Plantas</p>
            </div>
        </div>
    </div>

    {{-- Badges --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-serif font-light text-xl text-[#EDE0CC]">Badges</h3>
            <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D]">{{ $earnedCount }} de {{ count($badges) }}</p>
        </div>

        @php
            $categories = ['diário' => 'Diário Verde', 'cuidados' => 'Cuidados', 'streak' => 'Sequência', 'especial' => 'Especial'];
        @endphp

        @foreach($categories as $catKey => $catLabel)
        @php $catBadges = collect($badges)->where('category', $catKey)->values(); @endphp
        @if($catBadges->count())
        <div class="mb-6">
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">{{ $catLabel }}</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($catBadges as $badge)
                <div class="glass rounded-2xl p-4 flex flex-col items-center text-center gap-2 relative transition-all duration-200
                            {{ $badge['earned'] ? 'border border-[#C8A96E]/25' : 'opacity-45' }}">

                    @if($badge['earned'])
                    <div class="absolute top-3 right-3 w-2 h-2 rounded-full bg-[#C8A96E]"></div>
                    @endif

                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl
                                {{ $badge['earned'] ? 'bg-[#C8A96E]/15' : 'bg-white/[0.04]' }}">
                        {{ $badge['icon'] }}
                    </div>

                    <div>
                        <p class="text-[#EDE0CC] text-xs font-medium leading-tight">{{ $badge['title'] }}</p>
                        <p class="text-[#3A5E2D] text-[10px] mt-1 leading-snug">
                            @if($badge['earned'])
                                Conquistado {{ $badge['earned_at']?->diffForHumans() }}
                            @else
                                {{ $badge['desc'] }}
                            @endif
                        </p>
                    </div>

                    <span class="text-[9px] text-[#C8A96E]/70 uppercase tracking-wider mt-auto">
                        +{{ $badge['xp'] }} XP
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- Como funciona --}}
    <div class="glass rounded-2xl p-6 space-y-6">
        <h3 class="font-serif font-light text-xl text-[#EDE0CC]">Como funciona</h3>

        {{-- XP --}}
        <div>
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-3">Ganhe XP fazendo</p>
            <div class="space-y-2">
                @foreach(\App\Support\Gamification::XP_ACTIONS as $action)
                <div class="flex items-center justify-between py-2 border-b border-white/[0.04] last:border-0">
                    <p class="text-[#EDE0CC] text-xs">{{ $action['label'] }}</p>
                    <span class="text-[#C8A96E] text-xs font-medium">+{{ $action['xp'] }} XP</span>
                </div>
                @endforeach
                <div class="flex items-center justify-between py-2 border-b border-white/[0.04] last:border-0">
                    <p class="text-[#EDE0CC] text-xs">Ganhar um badge</p>
                    <span class="text-[#C8A96E] text-xs font-medium">+XP do badge</span>
                </div>
            </div>
        </div>

        {{-- Níveis --}}
        <div class="border-t border-white/[0.06] pt-6">
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-3">Níveis</p>
            <div class="space-y-2">
                @foreach(\App\Support\Gamification::LEVELS as $lvl)
                @php $isCurrent = $level['slug'] === $lvl['slug']; @endphp
                <div class="flex items-center gap-3 py-2 border-b border-white/[0.04] last:border-0
                            {{ $isCurrent ? 'opacity-100' : 'opacity-50' }}">
                    <span class="text-xl w-7 text-center">{{ $lvl['icon'] }}</span>
                    <p class="text-[#EDE0CC] text-xs flex-1">{{ $lvl['label'] }}</p>
                    <span class="text-[#3A5E2D] text-[10px]">
                        {{ $lvl['min'] }}{{ $lvl['next'] ? '–'.($lvl['next']-1) : '+' }} XP
                    </span>
                    @if($isCurrent)
                    <span class="text-[9px] bg-[#C8A96E]/15 text-[#C8A96E] px-2 py-0.5 rounded-full border border-[#C8A96E]/25">Atual</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Streak --}}
        <div class="border-t border-white/[0.06] pt-6">
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-3">Sequência (Streak)</p>
            <p class="text-[#7A8E72] text-xs leading-relaxed">
                Cada dia que você registrar pelo menos um cuidado (rega, adubação ou poda) no Diário Verde, sua sequência cresce. Se passar um dia sem registrar nenhum cuidado, ela volta a zero.
            </p>
            <div class="grid grid-cols-2 gap-3 mt-4">
                <div class="glass rounded-xl p-3 text-center">
                    <p class="text-lg mb-1">💧</p>
                    <p class="text-[#EDE0CC] text-xs">7 dias</p>
                    <p class="text-[#3A5E2D] text-[10px] mt-0.5">Badge Regador Fiel</p>
                </div>
                <div class="glass rounded-xl p-3 text-center">
                    <p class="text-lg mb-1">🔥</p>
                    <p class="text-[#EDE0CC] text-xs">30 dias</p>
                    <p class="text-[#3A5E2D] text-[10px] mt-0.5">Badge Sequência de Ouro</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
