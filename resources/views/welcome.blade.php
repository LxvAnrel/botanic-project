@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- ══════════════════════════════════════
     HERO
══════════════════════════════════════ --}}
<section class="relative min-h-[92vh] flex flex-col justify-center overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-[#060E05] via-[#0A1408] to-[#0E1A0B] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-5 md:px-8 lg:px-12 pt-20 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <div>
                <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-8">— Plataforma Botânica Interativa</p>

                <h1 class="font-serif font-light leading-none mb-6">
                    <span class="block text-[clamp(3.5rem,9vw,8rem)] text-[#C8A96E] leading-none" style="text-shadow: 0 0 80px rgba(200,169,110,0.3)">Natura</span>
                    <span class="block text-[clamp(1.4rem,3.5vw,2.75rem)] text-[#EDE0CC] tracking-wide mt-2 italic">em cada folha</span>
                </h1>

                <p class="text-[#7A8E72] text-sm leading-relaxed max-w-md mb-4">
                    Descubra, cultive e cuide das suas plantas com alertas inteligentes, diário pessoal e um sistema de conquistas que torna o cuidado verde um hábito prazeroso.
                </p>
                <p class="text-[#3A5E2D] text-xs leading-relaxed max-w-sm mb-10">
                    Mais de 20 espécies catalogadas · Notificações de rega, adubação e poda · 100% gratuito
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-8 py-4 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_30px_rgba(200,169,110,0.3)]">
                        Criar conta grátis
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('plants.index') }}"
                       class="inline-flex items-center gap-3 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest font-medium px-8 py-4 rounded-full transition-all duration-200">
                        Ver catálogo
                    </a>
                </div>
            </div>

            {{-- Decorativo com badges flutuantes --}}
            <div class="hidden lg:flex items-center justify-center">
                <div class="relative w-80 h-80">
                    {{-- Círculos concêntricos --}}
                    <div class="absolute inset-0 glass rounded-full flex items-center justify-center">
                        <div class="w-56 h-56 glass-gold rounded-full flex items-center justify-center">
                            <div class="w-36 h-36 glass-deep rounded-full flex items-center justify-center text-7xl">🌿</div>
                        </div>
                    </div>
                    {{-- Badges flutuando --}}
                    <div class="absolute -top-4 -right-4 glass-gold rounded-2xl px-3 py-2 flex items-center gap-2 shadow-lg">
                        <span class="text-base">🏆</span>
                        <div>
                            <p class="text-[8px] uppercase tracking-wider text-[#C8A96E]">Mestre Verde</p>
                            <p class="text-[9px] text-[#7A8E72]">1000 XP</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -left-4 glass rounded-2xl px-3 py-2 flex items-center gap-2">
                        <span class="text-base">🔥</span>
                        <div>
                            <p class="text-[8px] uppercase tracking-wider text-[#EDE0CC]">Streak</p>
                            <p class="text-[9px] text-[#C8A96E]">14 dias</p>
                        </div>
                    </div>
                    <div class="absolute top-1/2 -right-10 glass rounded-xl px-3 py-1.5 flex items-center gap-1.5">
                        <span class="text-sm">💧</span>
                        <p class="text-[9px] text-[#EDE0CC]">Hora de regar</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-16 pt-8 border-t border-white/[0.06] grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl">
            @php
                $stats = [
                    ['v' => '20+', 'l' => 'Espécies'],
                    ['v' => '3',   'l' => 'Tipos de alerta'],
                    ['v' => '11',  'l' => 'Badges'],
                    ['v' => '5',   'l' => 'Níveis'],
                ];
            @endphp
            @foreach($stats as $s)
            <div class="glass rounded-2xl px-4 py-4 text-center">
                <p class="font-serif text-2xl md:text-3xl text-[#C8A96E]" style="text-shadow:0 0 30px rgba(200,169,110,0.4)">{{ $s['v'] }}</p>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mt-1">{{ $s['l'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     COMO FUNCIONA
══════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-16 md:py-24">
    <div class="text-center mb-12 md:mb-16">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Do zero ao jardim</p>
        <h2 class="font-serif font-light text-3xl md:text-5xl text-[#EDE0CC]">Como <em class="text-[#C8A96E]">funciona</em></h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative">
        {{-- Linha de conexão (desktop) --}}
        <div class="hidden lg:block absolute top-10 left-[12.5%] right-[12.5%] h-px bg-gradient-to-r from-transparent via-[#C8A96E]/20 to-transparent pointer-events-none"></div>

        @php
            $steps = [
                ['num' => '01', 'icon' => '◎', 'title' => 'Explore o catálogo',   'desc' => 'Filtre por luz solar, porte e pet-friendly. Encontre a planta certa para cada canto da sua casa.'],
                ['num' => '02', 'icon' => '✦', 'title' => 'Faça o quiz',          'desc' => 'Responda 4 perguntas sobre seu espaço e receba a recomendação perfeita para o seu ambiente.'],
                ['num' => '03', 'icon' => '◉', 'title' => 'Monte seu Diário Verde','desc' => 'Salve as plantas que você tem ou quer ter. Registre regas, adubações e podas no histórico.'],
                ['num' => '04', 'icon' => '◇', 'title' => 'Receba alertas e XP',  'desc' => 'Notificações de poda, rega e adubação no momento certo. Ganhe XP e suba de nível cuidando.'],
            ];
        @endphp

        @foreach($steps as $step)
        <div class="glass rounded-3xl p-7 relative group hover:glass-gold transition-all duration-500">
            <div class="flex items-start justify-between mb-6">
                <span class="text-[#C8A96E] text-2xl group-hover:scale-110 transition-transform duration-300">{{ $step['icon'] }}</span>
                <span class="font-serif text-4xl text-white/[0.04] group-hover:text-white/[0.07] transition-colors duration-300 select-none">{{ $step['num'] }}</span>
            </div>
            <h3 class="font-serif text-lg text-[#EDE0CC] mb-3">{{ $step['title'] }}</h3>
            <p class="text-[#7A8E72] text-xs leading-relaxed">{{ $step['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ══════════════════════════════════════
     PRÉVIA DO CATÁLOGO
══════════════════════════════════════ --}}
@if($plantas->count() > 0)
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16">
    <div class="flex items-end justify-between mb-8 pb-6 border-b border-white/[0.06]">
        <div>
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-3">— Do catálogo</p>
            <h2 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC]">Algumas <em class="text-[#C8A96E]">espécies</em></h2>
        </div>
        <a href="{{ route('plants.index') }}"
           class="glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-5 py-2.5 rounded-full transition-all duration-200 whitespace-nowrap">
            Ver todas →
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($plantas as $planta)
        <a href="{{ route('plants.show', $planta) }}" class="group relative overflow-hidden block glass rounded-3xl">
            @if($planta->image_path)
                <img src="{{ asset($planta->image_path) }}"
                     alt="{{ $planta->nome_popular }}"
                     class="w-full h-64 md:h-80 object-cover group-hover:scale-105 transition-transform duration-700 opacity-80 group-hover:opacity-100">
            @else
                <div class="w-full h-64 md:h-80 bg-gradient-to-br from-[#1A3A1A] to-[#0D2010] flex items-center justify-center text-5xl opacity-30">🌿</div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1408]/95 via-[#0A1408]/20 to-transparent rounded-3xl"></div>

            @if(!$planta->toxica_pets)
            <div class="absolute top-4 left-4">
                <span class="text-[9px] uppercase tracking-wider bg-[#C8A96E]/15 text-[#C8A96E] border border-[#C8A96E]/25 px-3 py-1 rounded-full backdrop-blur-sm">Pet-safe</span>
            </div>
            @endif

            <div class="absolute bottom-0 left-0 right-0 p-6">
                <p class="font-serif text-xl text-[#EDE0CC] group-hover:text-[#C8A96E] transition-colors duration-300">{{ $planta->nome_popular }}</p>
                <p class="text-[#7A8E72] text-xs italic mt-0.5">{{ $planta->nome_cientifico }}</p>
                <div class="flex items-center gap-3 mt-3">
                    <span class="text-[9px] uppercase tracking-wider text-[#3A5E2D]">
                        @switch($planta->habitat_luz)
                            @case('sol_pleno') ☀ Sol pleno @break
                            @case('meia_sombra') ◑ Meia-sombra @break
                            @case('sombra') ● Sombra @break
                        @endswitch
                    </span>
                    <span class="text-[#3A5E2D] text-[9px]">· {{ $planta->porte_max_cm }}cm</span>
                </div>
            </div>

            <div class="absolute top-4 right-4 w-8 h-8 glass rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <svg class="w-3 h-3 text-[#C8A96E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ══════════════════════════════════════
     DIÁRIO VERDE + ALERTAS
══════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Diário Verde --}}
        <div class="glass rounded-3xl p-8 md:p-10 flex flex-col">
            <span class="text-3xl mb-6">🪴</span>
            <h3 class="font-serif font-light text-2xl md:text-3xl text-[#EDE0CC] mb-4">Diário Verde</h3>
            <p class="text-[#7A8E72] text-sm leading-relaxed mb-6">
                Monte seu acervo pessoal de plantas. Registre cada rega, adubação e poda — o histórico fica salvo e serve de base para os alertas automáticos.
            </p>
            <ul class="space-y-2.5 mb-8">
                @foreach(['Salve qualquer planta do catálogo com um toque','Histórico completo de cuidados por planta','Status de rega: em dia, próxima ou atrasada','Acesso rápido pelo painel do usuário'] as $item)
                <li class="flex items-center gap-2.5 text-xs text-[#7A8E72]">
                    <span class="w-1 h-1 rounded-full bg-[#C8A96E] shrink-0"></span>{{ $item }}
                </li>
                @endforeach
            </ul>
            <a href="{{ route('register') }}" class="mt-auto self-start glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
                Criar meu diário →
            </a>
        </div>

        {{-- Alertas inteligentes --}}
        <div class="glass rounded-3xl p-8 md:p-10 flex flex-col">
            <span class="text-3xl mb-6">🔔</span>
            <h3 class="font-serif font-light text-2xl md:text-3xl text-[#EDE0CC] mb-4">Alertas inteligentes</h3>
            <p class="text-[#7A8E72] text-sm leading-relaxed mb-6">
                O sistema monitora suas plantas e avisa na hora certa — por e-mail ou notificação push no celular. Nunca mais esqueça de cuidar do seu verde.
            </p>
            <div class="space-y-3 mb-8">
                @php
                    $alerts = [
                        ['icon' => '✂', 'color' => 'text-[#C8A96E]',  'bg' => 'bg-[#C8A96E]/10',  'title' => 'Poda sazonal',   'desc' => 'Um aviso por estação, no momento certo'],
                        ['icon' => '💧', 'color' => 'text-blue-400',   'bg' => 'bg-blue-400/10',   'title' => 'Rega atrasada',  'desc' => 'Baseado no intervalo ideal de cada planta'],
                        ['icon' => '🌱', 'color' => 'text-[#7A8E72]',  'bg' => 'bg-[#7A8E72]/10',  'title' => 'Adubação',       'desc' => 'Lembrete mensal para nutrir o solo'],
                    ];
                @endphp
                @foreach($alerts as $a)
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-9 h-9 {{ $a['bg'] }} rounded-full flex items-center justify-center text-base">{{ $a['icon'] }}</div>
                    <div>
                        <p class="text-[#EDE0CC] text-xs font-medium">{{ $a['title'] }}</p>
                        <p class="text-[#3A5E2D] text-[10px]">{{ $a['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('register') }}" class="mt-auto self-start glass border border-white/[0.07] hover:border-[#C8A96E]/30 text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
                Ativar alertas →
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     GAMIFICAÇÃO
══════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16">
    <div class="glass-gold rounded-3xl p-8 md:p-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

            <div>
                <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Torne o cuidado um hábito</p>
                <h2 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC] mb-5">
                    Conquistas &<br><em class="text-[#C8A96E]">progressão</em>
                </h2>
                <p class="text-[#7A8E72] text-sm leading-relaxed mb-6">
                    Cada cuidado registrado rende XP. Mantenha sua sequência diária, suba de nível e desbloqueie badges que mostram sua evolução como jardineiro.
                </p>

                {{-- Níveis --}}
                <div class="flex flex-wrap gap-2">
                    @foreach([['🌱','Muda'],['🪴','Cultivador'],['🌿','Jardineiro'],['🌳','Botanista'],['🏆','Mestre Verde']] as $lvl)
                    <div class="glass rounded-full px-3 py-1.5 flex items-center gap-1.5">
                        <span class="text-sm">{{ $lvl[0] }}</span>
                        <span class="text-[9px] uppercase tracking-wider text-[#EDE0CC]">{{ $lvl[1] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Grade de badges --}}
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-4">11 badges para colecionar</p>
                <div class="grid grid-cols-4 gap-3">
                    @php
                        $badgePreview = [
                            ['🌱','Primeira Folha'],['🪴','Colecionador'],['🌳','Jardim Completo'],['🔬','Botanista'],
                            ['🐾','Pet Lover'],['🌺','Biodiversidade'],['🤲','Mãos na Terra'],['✂','Podador'],
                            ['💧','Reg. Fiel'],['🔥','Seq. de Ouro'],['🧠','Quiz Botânico'],
                        ];
                    @endphp
                    @foreach($badgePreview as $i => $badge)
                    <div class="glass rounded-2xl p-3 flex flex-col items-center gap-1.5 text-center
                                {{ $i < 4 ? 'opacity-100 border border-[#C8A96E]/20' : 'opacity-40' }}">
                        <span class="text-xl">{{ $badge[0] }}</span>
                        <p class="text-[8px] uppercase tracking-wide text-[#7A8E72] leading-tight">{{ $badge[1] }}</p>
                    </div>
                    @endforeach
                    {{-- Célula "e mais" --}}
                    <div class="glass rounded-2xl p-3 flex flex-col items-center justify-center gap-1 opacity-30">
                        <p class="text-[#C8A96E] text-lg font-light">+</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     QUIZ
══════════════════════════════════════ --}}
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16">
    <div class="glass rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1">
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Recomendação personalizada</p>
            <h2 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC] mb-4">
                Não sabe<br>qual planta <em class="text-[#C8A96E]">escolher?</em>
            </h2>
            <p class="text-[#7A8E72] text-sm leading-relaxed mb-6 max-w-sm">
                O quiz considera a luz disponível, tamanho do espaço, presença de pets e seu nível de experiência para indicar a planta ideal.
            </p>
            <div class="flex flex-wrap gap-3 mb-6">
                @foreach(['Luz disponível','Tamanho do espaço','Pet na casa?','Experiência'] as $q)
                <span class="glass rounded-full px-4 py-1.5 text-[10px] uppercase tracking-wider text-[#7A8E72] flex items-center gap-1.5">
                    <span class="text-[#C8A96E]">→</span> {{ $q }}
                </span>
                @endforeach
            </div>
            <a href="{{ route('quiz') }}"
               class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-8 py-4 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_20px_rgba(200,169,110,0.25)]">
                Fazer o quiz
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        {{-- Visual do quiz --}}
        <div class="shrink-0 w-full md:w-72">
            <div class="glass rounded-2xl p-5 space-y-3">
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Quiz de recomendação</p>
                @foreach(['Quanta luz tem o ambiente?','O espaço é pequeno ou grande?','Tem animais em casa?','Você já cultivou antes?'] as $i => $pergunta)
                <div class="flex items-center gap-3">
                    <span class="shrink-0 w-5 h-5 rounded-full {{ $i < 2 ? 'bg-[#C8A96E] text-[#0E1A0B]' : 'glass' }} flex items-center justify-center text-[9px] font-bold">{{ $i+1 }}</span>
                    <p class="text-[#EDE0CC] text-xs">{{ $pergunta }}</p>
                </div>
                @endforeach
                <div class="pt-3 border-t border-white/[0.06]">
                    <p class="text-[9px] text-[#3A5E2D] uppercase tracking-wider">→ Resultado personalizado em segundos</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════
     CTA FINAL
══════════════════════════════════════ --}}
@guest
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16 mb-4">
    <div class="relative glass-gold rounded-3xl px-8 md:px-14 py-14 md:py-20 overflow-hidden text-center">
        {{-- Orbs decorativos --}}
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-[#C8A96E]/5 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-[#2D6A2D]/10 blur-3xl pointer-events-none"></div>

        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-5">— Comece agora, de graça</p>
        <h2 class="font-serif font-light text-4xl md:text-6xl text-[#EDE0CC] mb-4">
            Seu jardim começa<br><em class="text-[#C8A96E]">aqui</em>
        </h2>
        <p class="text-[#7A8E72] text-sm leading-relaxed max-w-md mx-auto mb-10">
            Crie sua conta em segundos, explore o catálogo, monte seu Diário Verde e deixe os alertas cuidarem do resto.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-3 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-10 py-5 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_40px_rgba(200,169,110,0.35)]">
                Criar conta grátis
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-3 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest font-medium px-10 py-5 rounded-full transition-all duration-200">
                Já tenho conta
            </a>
        </div>
    </div>
</section>
@endguest

@auth
<section class="max-w-7xl mx-auto px-5 md:px-8 lg:px-12 py-8 md:py-16 mb-4">
    <div class="glass-gold rounded-3xl px-8 md:px-14 py-12 flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-2">— Olá, {{ auth()->user()->nickname ?? auth()->user()->name }}</p>
            <h2 class="font-serif font-light text-3xl text-[#EDE0CC]">Continue cultivando <em class="text-[#C8A96E]">seu verde</em></h2>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-[#C8A96E] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold px-7 py-3.5 rounded-full hover:bg-[#D4BA8A] transition-all duration-200">
                Meu Diário →
            </a>
            <a href="{{ route('conquistas') }}" class="inline-flex items-center gap-2 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest px-7 py-3.5 rounded-full transition-all duration-200">
                Conquistas
            </a>
        </div>
    </div>
</section>
@endauth

@endsection
