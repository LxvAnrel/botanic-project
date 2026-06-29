@extends('admin.layout')
@section('title', 'Plantas')
@section('header_actions')
    <a href="/admin/plantas/criar"
       class="flex items-center gap-2 bg-[#C8A96E] text-[#0B160A] text-[10px] uppercase tracking-widest font-semibold px-4 py-2 rounded-xl hover:bg-[#D4BA8A] transition-all">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Nova planta
    </a>
@endsection

@section('content')

{{-- Barra de busca e filtros --}}
<form method="GET" id="plant-filter-form" class="space-y-3 mb-6">

    {{-- Busca --}}
    <div class="flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Buscar por nome popular ou científico…"
               class="flex-1 glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
        <button class="glass border border-white/[0.08] text-[#C8A96E] text-xs uppercase tracking-widest px-5 py-2.5 rounded-xl hover:border-[#C8A96E]/40 transition-all shrink-0">
            Buscar
        </button>
        @if(request()->hasAny(['q','familia','luz','pet']))
        <a href="/admin/plantas"
           class="glass border border-white/[0.05] text-[#7A8E72] text-xs uppercase tracking-widest px-4 py-2.5 rounded-xl hover:text-[#C8A96E] transition-all shrink-0"
           title="Limpar filtros">✕</a>
        @endif
    </div>

    {{-- Filtros em linha --}}
    <div class="flex flex-wrap gap-2">

        {{-- Família --}}
        @if($familias->isNotEmpty())
        <select name="familia" onchange="document.getElementById('plant-filter-form').submit()"
                class="glass border-white/[0.08] text-[#7A8E72] text-[10px] uppercase tracking-widest px-3 py-2 rounded-full focus:outline-none focus:border-[#C8A96E]/40 cursor-pointer {{ request('familia') ? 'border-[#C8A96E]/40 text-[#C8A96E]' : '' }}">
            <option value="">— Família —</option>
            @foreach($familias as $f)
            <option value="{{ $f }}" {{ request('familia') === $f ? 'selected' : '' }}>{{ $f }}</option>
            @endforeach
        </select>
        @endif

        {{-- Luminosidade --}}
        <select name="luz" onchange="document.getElementById('plant-filter-form').submit()"
                class="glass border-white/[0.08] text-[#7A8E72] text-[10px] uppercase tracking-widest px-3 py-2 rounded-full focus:outline-none focus:border-[#C8A96E]/40 cursor-pointer {{ request('luz') ? 'border-[#C8A96E]/40 text-[#C8A96E]' : '' }}">
            <option value="">— Luz —</option>
            <option value="sol_pleno"   {{ request('luz') === 'sol_pleno'   ? 'selected' : '' }}>☀ Sol Pleno</option>
            <option value="meia_sombra" {{ request('luz') === 'meia_sombra' ? 'selected' : '' }}>◑ Meia Sombra</option>
            <option value="sombra"      {{ request('luz') === 'sombra'      ? 'selected' : '' }}>● Sombra</option>
        </select>

        {{-- Pets --}}
        <select name="pet" onchange="document.getElementById('plant-filter-form').submit()"
                class="glass border-white/[0.08] text-[#7A8E72] text-[10px] uppercase tracking-widest px-3 py-2 rounded-full focus:outline-none focus:border-[#C8A96E]/40 cursor-pointer {{ request('pet') !== null && request('pet') !== '' ? 'border-[#C8A96E]/40 text-[#C8A96E]' : '' }}">
            <option value="">— Pets —</option>
            <option value="0" {{ request('pet') === '0' ? 'selected' : '' }}>🐾 Pet-friendly</option>
            <option value="1" {{ request('pet') === '1' ? 'selected' : '' }}>⚠ Tóxica</option>
        </select>

        @if(request()->hasAny(['familia','luz','pet']))
        <span class="text-[9px] uppercase tracking-widest text-[#C8A96E]/60 self-center pl-1">
            {{ $plantas->total() }} resultado{{ $plantas->total() != 1 ? 's' : '' }}
        </span>
        @endif
    </div>
</form>

{{-- Grade de plantas em cards --}}
@if($plantas->isEmpty())
<div class="glass rounded-2xl p-12 text-center">
    <p class="text-[#3A5E2D] text-sm">Nenhuma planta encontrada com esses filtros.</p>
</div>
@else
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
    @foreach($plantas as $p)
    <div class="glass rounded-2xl overflow-hidden flex flex-col group">

        {{-- Imagem --}}
        <a href="/admin/plantas/{{ $p->id }}/editar" class="block relative">
            @if($p->image_path)
                <img src="{{ asset($p->image_path) }}" alt="{{ $p->nome_popular }}"
                     class="w-full h-32 object-cover opacity-80 group-hover:opacity-100 transition-opacity">
            @else
                <div class="w-full h-32 bg-gradient-to-br from-[#1A3A1A] to-[#0D2010] flex items-center justify-center text-4xl opacity-20">
                    🌿
                </div>
            @endif
            {{-- Badges sobrepostos --}}
            <div class="absolute bottom-2 left-2 flex gap-1">
                <span class="text-[8px] glass px-2 py-0.5 rounded-full text-[#C8A96E]">
                    {{ ['sol_pleno' => '☀', 'meia_sombra' => '◑', 'sombra' => '●'][$p->habitat_luz] ?? '' }}
                </span>
                @if($p->toxica_pets)
                <span class="text-[8px] glass px-2 py-0.5 rounded-full text-red-400">⚠</span>
                @endif
            </div>
        </a>

        {{-- Info --}}
        <div class="p-3 flex-1 flex flex-col gap-1 min-w-0">
            <a href="/admin/plantas/{{ $p->id }}/editar"
               class="text-[#EDE0CC] text-xs font-medium leading-snug hover:text-[#C8A96E] transition-colors line-clamp-2">{{ $p->nome_popular }}</a>
            @if($p->familia)
            <p class="text-[#3A5E2D] text-[9px] uppercase tracking-wider truncate">{{ $p->familia }}</p>
            @endif
            <p class="text-[#7A8E72] text-[10px] mt-auto">{{ $p->users_count }} diário{{ $p->users_count != 1 ? 's' : '' }}</p>
        </div>

        {{-- Ações --}}
        <div class="px-3 pb-3 flex items-center gap-3">
            <a href="/admin/plantas/{{ $p->id }}/editar"
               class="flex-1 text-center text-[9px] uppercase tracking-widest text-[#C8A96E] glass border border-white/[0.06] hover:border-[#C8A96E]/30 px-2 py-1.5 rounded-lg transition-all">
                Editar
            </a>
            <form method="POST" action="/admin/plantas/{{ $p->id }}"
                  onsubmit="return confirm('Remover {{ addslashes($p->nome_popular) }}?')">
                @csrf @method('DELETE')
                <button class="text-[9px] uppercase tracking-widest text-red-400/40 hover:text-red-400 transition-colors py-1.5">✕</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($plantas->hasPages())
<div class="mt-6">{{ $plantas->links() }}</div>
@endif
@endif

@endsection
