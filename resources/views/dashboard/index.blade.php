@extends('layouts.app')

@section('title', 'Meu Diário Verde')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">

    <div class="flex items-end justify-between mb-12 border-b border-white/[0.06] pb-8">
        <div>
            <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">— Sua coleção</p>
            <h1 class="font-serif font-light text-5xl text-[#EDE0CC]">Diário Verde</h1>
        </div>
        <a href="{{ route('plants.index') }}"
           class="glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-xs uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
            + Adicionar plantas
        </a>
    </div>

    @if($plantas->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($plantas as $planta)
            <a href="{{ route('plants.show', $planta) }}" class="group relative overflow-hidden block glass rounded-3xl">

                @if($planta->image_path)
                    <img src="{{ asset('storage/' . $planta->image_path) }}"
                         alt="{{ $planta->nome_popular }}"
                         class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-700 opacity-70 group-hover:opacity-90">
                @else
                    @php
                        $bgs = ['from-[#1A3A1A] to-[#0D2010]','from-[#1E3520] to-[#112015]','from-[#15301A] to-[#0A1D0D]','from-[#1A3525] to-[#0D2018]'];
                        $icons = ['🌿','🪴','🌱','🍃','🌾','🌳'];
                    @endphp
                    <div class="w-full h-60 bg-gradient-to-br {{ $bgs[$loop->index % 4] }} flex items-center justify-center text-5xl opacity-25 group-hover:opacity-40 transition-opacity duration-300">
                        {{ $icons[$loop->index % 6] }}
                    </div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1408]/95 via-[#0A1408]/20 to-transparent rounded-3xl"></div>

                <div class="absolute bottom-0 left-0 right-0 p-5">
                    <p class="font-serif text-lg text-[#EDE0CC] group-hover:text-[#C8A96E] transition-colors duration-300">{{ $planta->nome_popular }}</p>
                    <p class="text-[#7A8E72] text-xs italic">{{ $planta->nome_cientifico }}</p>
                    <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D] mt-2">
                        Adicionada {{ $planta->pivot->created_at->diffForHumans() }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $plantas->links() }}</div>

    @else
        <div class="glass rounded-3xl p-24 text-center">
            <p class="font-serif text-6xl text-[#22381B] mb-6">∅</p>
            <p class="font-serif font-light text-2xl text-[#EDE0CC] mb-2">Seu diário está vazio</p>
            <p class="text-[#7A8E72] text-sm mb-10">Explore o catálogo e salve as plantas que você quer cultivar.</p>
            <a href="{{ route('plants.index') }}"
               class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-8 py-4 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_30px_rgba(200,169,110,0.3)]">
                Explorar catálogo
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    @endif
</div>
@endsection
