@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="max-w-2xl mx-auto px-5 md:px-8 py-12 md:py-20">

    {{-- Header do perfil --}}
    <div class="glass-gold rounded-3xl p-8 md:p-10 mb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        {{-- Avatar --}}
        <div class="shrink-0">
            @if($user->avatar_path)
                <img src="{{ asset('storage/' . $user->avatar_path) }}"
                     alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover border-2 border-[#C8A96E]/40">
            @else
                <div class="w-24 h-24 rounded-full bg-[#C8A96E]/15 border-2 border-[#C8A96E]/40 flex items-center justify-center text-4xl font-serif font-light text-[#C8A96E]">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 text-center sm:text-left min-w-0">
            <h1 class="font-serif font-light text-3xl text-[#EDE0CC] mb-1">{{ $user->name }}</h1>
            <div class="flex items-center justify-center sm:justify-start gap-2 mb-3">
                <span class="text-lg">{{ $progress['level']['icon'] }}</span>
                <span class="text-[10px] uppercase tracking-widest text-[#C8A96E]">{{ $progress['level']['label'] }}</span>
                <span class="text-[#3A5E2D] text-[10px]">· {{ $user->xp ?? 0 }} XP</span>
            </div>
            @if($user->bio)
            <p class="text-[#9AA88E] text-sm leading-relaxed">{{ $user->bio }}</p>
            @endif
        </div>
    </div>

    {{-- Stats rápidas --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        @php
            $earnedCount = collect($badges)->where('earned', true)->count();
        @endphp
        <div class="glass rounded-2xl p-4 text-center">
            <p class="font-serif text-2xl text-[#C8A96E]">{{ $plants->count() }}</p>
            <p class="text-[9px] uppercase tracking-wider text-[#7A8E72] mt-1">Plantas</p>
        </div>
        <div class="glass rounded-2xl p-4 text-center">
            <p class="font-serif text-2xl text-[#C8A96E]">{{ $earnedCount }}</p>
            <p class="text-[9px] uppercase tracking-wider text-[#7A8E72] mt-1">Badges</p>
        </div>
        <div class="glass rounded-2xl p-4 text-center">
            <p class="font-serif text-2xl text-[#C8A96E]">{{ $user->streak_days ?? 0 }}</p>
            <p class="text-[9px] uppercase tracking-wider text-[#7A8E72] mt-1">Dias streak</p>
        </div>
    </div>

    {{-- Barra de progresso de nível --}}
    <div class="glass rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between mb-2">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Progresso de nível</p>
            @if($progress['nextLevel'])
            <p class="text-[9px] text-[#3A5E2D]">{{ $progress['needed'] }} XP para {{ $progress['nextLevel']['label'] }}</p>
            @else
            <p class="text-[9px] text-[#C8A96E]">Nível máximo atingido 🏆</p>
            @endif
        </div>
        <div class="w-full bg-white/[0.05] rounded-full h-2">
            <div class="bg-[#C8A96E] h-2 rounded-full transition-all duration-700"
                 style="width: {{ $progress['percent'] }}%"></div>
        </div>
    </div>

    {{-- Badges --}}
    @if($earnedCount > 0)
    <div class="glass rounded-3xl p-6 mb-6">
        <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-5">Conquistas desbloqueadas</p>
        <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
            @foreach($badges as $badge)
            @if($badge['earned'])
            <div class="group relative" title="{{ $badge['title'] }}">
                <div class="glass-gold rounded-2xl p-3 flex flex-col items-center gap-1.5 border border-[#C8A96E]/20">
                    <span class="text-xl">{{ $badge['icon'] }}</span>
                    <p class="text-[8px] uppercase tracking-wide text-[#C8A96E] leading-tight text-center">{{ $badge['title'] }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Plantas do diário (sem dados privados) --}}
    @if($plants->count() > 0)
    <div class="glass rounded-3xl p-6">
        <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-5">Diário Verde</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($plants as $plant)
            <a href="{{ route('plants.show', $plant) }}" class="group glass rounded-2xl overflow-hidden hover:glass-gold transition-all duration-200">
                @if($plant->image_path)
                    <img src="{{ asset($plant->image_path) }}" class="w-full h-24 object-cover opacity-70 group-hover:opacity-90 transition-opacity">
                @else
                    <div class="w-full h-24 bg-gradient-to-br from-[#1A3A1A] to-[#0D2010] flex items-center justify-center text-3xl opacity-30">🌿</div>
                @endif
                <div class="px-3 py-2">
                    <p class="text-[#EDE0CC] text-xs truncate group-hover:text-[#C8A96E] transition-colors">{{ $plant->nome_popular }}</p>
                    <p class="text-[#7A8E72] text-[9px] italic truncate">{{ $plant->nome_cientifico }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @if($user->plants()->count() > 6)
        <p class="text-center text-[10px] text-[#3A5E2D] mt-3">+{{ $user->plants()->count() - 6 }} plantas no diário</p>
        @endif
    </div>
    @endif

    <div class="mt-8 text-center">
        <a href="{{ route('comunidade') }}" class="text-[10px] uppercase tracking-widest text-[#7A8E72] hover:text-[#C8A96E] transition-colors">
            ← Ver comunidade
        </a>
    </div>

</div>
@endsection
