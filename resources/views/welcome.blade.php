@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- Hero --}}
<section class="relative min-h-[88vh] flex flex-col justify-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#0A1408] via-[#0E1A0B] to-[#0E1A0B] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-8">— Plataforma Botânica Interativa</p>

                <h1 class="font-serif font-light leading-none mb-6">
                    <span class="block text-[clamp(4rem,10vw,9rem)] text-[#C8A96E] leading-none" style="text-shadow: 0 0 80px rgba(200,169,110,0.25)">Natura</span>
                    <span class="block text-[clamp(1.5rem,4vw,3rem)] text-[#EDE0CC] tracking-wide mt-2 italic">em cada folha</span>
                </h1>

                <p class="text-[#7A8E72] text-sm leading-relaxed max-w-sm mb-10">
                    Explore centenas de espécies, receba recomendações personalizadas e organize seu acervo verde com elegância.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('plants.index') }}"
                       class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-8 py-4 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_30px_rgba(200,169,110,0.3)]">
                        Explorar catálogo
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('quiz') }}"
                       class="inline-flex items-center gap-3 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest font-medium px-8 py-4 rounded-full transition-all duration-200">
                        Fazer o quiz
                    </a>
                </div>
            </div>

            {{-- Decorativo --}}
            <div class="hidden lg:flex items-center justify-center">
                <div class="relative">
                    <div class="w-72 h-72 glass rounded-full flex items-center justify-center">
                        <div class="w-52 h-52 glass-gold rounded-full flex items-center justify-center">
                            <div class="w-36 h-36 glass-deep rounded-full flex items-center justify-center text-7xl">
                                🌿
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-5 -right-5 w-16 h-16 glass-gold rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 glass rounded-full"></div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-20 pt-10 border-t border-white/[0.06] grid grid-cols-3 gap-4 max-w-lg">
            @php $stats = [['v'=>'21+','l'=>'Espécies'],['v'=>'4','l'=>'Etapas do quiz'],['v'=>'∞','l'=>'Descobertas']]; @endphp
            @foreach($stats as $s)
            <div class="glass rounded-2xl px-6 py-5 text-center">
                <p class="font-serif text-3xl text-[#C8A96E]" style="text-shadow:0 0 30px rgba(200,169,110,0.4)">{{ $s['v'] }}</p>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mt-1">{{ $s['l'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Seção: Features --}}
<section class="max-w-7xl mx-auto px-6 lg:px-10 py-24">
    <div class="flex items-end justify-between mb-16 border-b border-white/[0.06] pb-8">
        <h2 class="font-serif font-light text-5xl text-[#EDE0CC]">Tudo que<br><em class="text-[#C8A96E]">você precisa</em></h2>
        <p class="text-[#7A8E72] text-sm max-w-xs text-right hidden md:block">
            Da descoberta ao cuidado diário, a Flora transforma sua relação com as plantas.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $features = [
                ['icon' => '◎', 'title' => 'Catálogo', 'desc' => 'Filtre por luz, tamanho ou compatibilidade com pets em tempo real.'],
                ['icon' => '◈', 'title' => 'Quiz', 'desc' => '4 perguntas e o sistema encontra a planta ideal para o seu ambiente.'],
                ['icon' => '◉', 'title' => 'Diário Verde', 'desc' => 'Salve suas plantas favoritas e acompanhe seu acervo pessoal.'],
                ['icon' => '◇', 'title' => 'Alertas de Poda', 'desc' => 'Notificações automáticas na época certa de podar cada planta.'],
            ];
        @endphp
        @foreach($features as $f)
        <div class="glass rounded-3xl p-8 hover:glass-gold transition-all duration-500 group">
            <p class="text-[#C8A96E] text-2xl mb-6 group-hover:scale-110 inline-block transition-transform duration-300">{{ $f['icon'] }}</p>
            <h3 class="font-serif text-xl text-[#EDE0CC] mb-3">{{ $f['title'] }}</h3>
            <p class="text-[#7A8E72] text-sm leading-relaxed">{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
@guest
<section class="max-w-7xl mx-auto px-6 lg:px-10 py-16 mb-8">
    <div class="glass-gold rounded-3xl px-10 py-16 flex flex-col md:flex-row items-center justify-between gap-10">
        <div>
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Comece agora</p>
            <h2 class="font-serif font-light text-5xl text-[#EDE0CC]">Crie sua conta<br><em class="text-[#C8A96E]">gratuitamente</em></h2>
        </div>
        <a href="{{ route('register') }}"
           class="flex-shrink-0 inline-flex items-center gap-4 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-10 py-5 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_30px_rgba(200,169,110,0.35)]">
            Criar conta
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endguest

@endsection
