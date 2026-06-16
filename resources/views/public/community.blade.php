@extends('layouts.app')

@section('title', 'Comunidade')

@section('content')
<div class="max-w-4xl mx-auto px-5 md:px-8 py-12 md:py-20">

    <div class="mb-10 pb-8 border-b border-white/[0.06]">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Jardineiros Flora</p>
        <h1 class="font-serif font-light text-4xl md:text-5xl text-[#EDE0CC]">Top <em class="text-[#C8A96E]">10</em> cultivadores</h1>
        <p class="text-[#7A8E72] text-sm mt-3">Os jardineiros mais engajados da plataforma, por XP acumulado.</p>
    </div>

    @if($top->isEmpty())
    <div class="glass rounded-3xl p-12 text-center">
        <p class="text-4xl mb-4">🌱</p>
        <p class="text-[#7A8E72] text-sm">Nenhum perfil público ainda. Seja o primeiro!</p>
    </div>
    @else

    {{-- Pódio top 3 --}}
    @if($top->count() >= 3)
    <div class="grid grid-cols-3 gap-3 mb-6 items-end">
        {{-- 2º lugar --}}
        <div class="glass rounded-3xl p-5 text-center">
            <div class="text-2xl mb-3">🥈</div>
            @if($top[1]->avatar_path)
                <img src="{{ $top[1]->avatar_url }}" class="w-14 h-14 rounded-full object-cover mx-auto mb-2 border-2 border-white/20">
            @else
                <div class="w-14 h-14 rounded-full bg-[#C8A96E]/10 border-2 border-white/20 flex items-center justify-center text-lg font-bold text-[#C8A96E] mx-auto mb-2">
                    {{ mb_strtoupper(mb_substr($top[1]->name, 0, 1)) }}
                </div>
            @endif
            <a href="{{ route('perfil.publico', $top[1]) }}" class="block font-serif text-[#EDE0CC] hover:text-[#C8A96E] transition-colors truncate text-sm">{{ $top[1]->name }}</a>
            <p class="text-[9px] text-[#7A8E72] mt-1">{{ $top[1]->levelData['icon'] }} {{ $top[1]->levelData['label'] }}</p>
            <p class="text-[#C8A96E] text-xs font-medium mt-1">{{ $top[1]->xp ?? 0 }} XP</p>
        </div>

        {{-- 1º lugar --}}
        <div class="glass-gold rounded-3xl p-5 text-center border border-[#C8A96E]/20">
            <div class="text-3xl mb-3">🥇</div>
            @if($top[0]->avatar_path)
                <img src="{{ $top[0]->avatar_url }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-[#C8A96E]/50">
            @else
                <div class="w-16 h-16 rounded-full bg-[#C8A96E]/20 border-2 border-[#C8A96E]/50 flex items-center justify-center text-xl font-bold text-[#C8A96E] mx-auto mb-2">
                    {{ mb_strtoupper(mb_substr($top[0]->name, 0, 1)) }}
                </div>
            @endif
            <a href="{{ route('perfil.publico', $top[0]) }}" class="block font-serif text-[#EDE0CC] hover:text-[#C8A96E] transition-colors truncate">{{ $top[0]->name }}</a>
            <p class="text-[9px] text-[#7A8E72] mt-1">{{ $top[0]->levelData['icon'] }} {{ $top[0]->levelData['label'] }}</p>
            <p class="text-[#C8A96E] text-sm font-semibold mt-1">{{ $top[0]->xp ?? 0 }} XP</p>
        </div>

        {{-- 3º lugar --}}
        <div class="glass rounded-3xl p-5 text-center">
            <div class="text-2xl mb-3">🥉</div>
            @if($top[2]->avatar_path)
                <img src="{{ $top[2]->avatar_url }}" class="w-14 h-14 rounded-full object-cover mx-auto mb-2 border-2 border-white/20">
            @else
                <div class="w-14 h-14 rounded-full bg-[#C8A96E]/10 border-2 border-white/20 flex items-center justify-center text-lg font-bold text-[#C8A96E] mx-auto mb-2">
                    {{ mb_strtoupper(mb_substr($top[2]->name, 0, 1)) }}
                </div>
            @endif
            <a href="{{ route('perfil.publico', $top[2]) }}" class="block font-serif text-[#EDE0CC] hover:text-[#C8A96E] transition-colors truncate text-sm">{{ $top[2]->name }}</a>
            <p class="text-[9px] text-[#7A8E72] mt-1">{{ $top[2]->levelData['icon'] }} {{ $top[2]->levelData['label'] }}</p>
            <p class="text-[#C8A96E] text-xs font-medium mt-1">{{ $top[2]->xp ?? 0 }} XP</p>
        </div>
    </div>
    @endif

    {{-- Lista 4–10 --}}
    @if($top->count() > 3)
    <div class="space-y-2">
        @foreach($top->slice(3) as $i => $u)
        <a href="{{ route('perfil.publico', $u) }}"
           class="glass rounded-2xl px-5 py-4 flex items-center gap-4 hover:glass-gold transition-all duration-200 group">
            <span class="w-7 text-center font-serif text-lg text-[#3A5E2D] shrink-0">{{ $i + 4 }}</span>

            @if($u->avatar_path)
                <img src="{{ $u->avatar_url }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-white/10">
            @else
                <div class="w-10 h-10 rounded-full bg-[#C8A96E]/10 border border-white/10 flex items-center justify-center text-sm font-bold text-[#C8A96E] shrink-0">
                    {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-[#EDE0CC] text-sm font-medium group-hover:text-[#C8A96E] transition-colors truncate">{{ $u->name }}</p>
                <p class="text-[#7A8E72] text-[10px]">{{ $u->levelData['icon'] }} {{ $u->levelData['label'] }} · {{ $u->badgesEarned }} badge{{ $u->badgesEarned != 1 ? 's' : '' }}</p>
            </div>

            <div class="text-right shrink-0">
                <p class="text-[#C8A96E] text-sm font-semibold">{{ $u->xp ?? 0 }}</p>
                <p class="text-[#3A5E2D] text-[9px] uppercase tracking-wider">XP</p>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    @endif

    {{-- CTA para tornar perfil público --}}
    @auth
    @if(!auth()->user()->profile_public)
    <div class="mt-8 glass rounded-2xl p-5 flex items-center justify-between gap-4">
        <div>
            <p class="text-[#EDE0CC] text-sm font-medium">Seu perfil está privado</p>
            <p class="text-[#7A8E72] text-xs mt-0.5">Ative para aparecer no ranking da comunidade.</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="glass-gold text-[#C8A96E] text-[10px] uppercase tracking-widest px-5 py-2.5 rounded-full whitespace-nowrap shrink-0">
            Ativar →
        </a>
    </div>
    @endif
    @endauth
</div>
@endsection
