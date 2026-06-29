@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')

{{-- Cards com as principais metricas do sistema --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
    @php
    $cards = [
        ['label' => 'Usuários total',    'value' => $stats['usuarios'],             'sub' => '+' . $stats['usuarios_hoje'] . ' hoje',  'cor' => '#C8A96E'],
        ['label' => 'Com plantas',       'value' => $stats['diarios_ativos'],        'sub' => 'diários ativos',                          'cor' => '#7AC77A'],
        ['label' => 'Notif. hoje',       'value' => $stats['notificacoes_hoje'],     'sub' => $stats['notificacoes_nao_lidas'] . ' não lidas', 'cor' => '#C8A96E'],
        ['label' => 'Ativos 7d',         'value' => $stats['ativos_7d'],             'sub' => $stats['ativos_30d'] . ' em 30d',          'cor' => '#7A8E72'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="glass rounded-2xl p-4 md:p-5">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mb-2 truncate">{{ $card['label'] }}</p>
        <p class="font-serif text-2xl md:text-3xl text-[#EDE0CC] mb-1" style="color:{{ $card['cor'] }}">{{ $card['value'] }}</p>
        <p class="text-[10px] text-[#3A5E2D]">{{ $card['sub'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Ultimos usuarios cadastrados --}}
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-[10px] uppercase tracking-widest text-[#7A8E72]">Últimos cadastros</h2>
            <a href="/admin/usuarios" class="text-[9px] uppercase tracking-widest text-[#C8A96E] hover:underline">Ver todos</a>
        </div>
        <div class="space-y-3">
            @foreach($recentes as $u)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#EDE0CC]">{{ $u->nickname ?? $u->name }}</p>
                    <p class="text-[10px] text-[#3A5E2D]">{{ $u->email }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-[#7A8E72]">{{ $u->created_at->diffForHumans() }}</p>
                    <p class="text-[10px] text-[#C8A96E]">{{ $u->xp ?? 0 }} XP</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Resumo do catalogo de plantas --}}
    <div class="glass rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-[10px] uppercase tracking-widest text-[#7A8E72]">Catálogo de plantas</h2>
            <a href="/admin/plantas" class="text-[9px] uppercase tracking-widest text-[#C8A96E] hover:underline">Gerenciar</a>
        </div>
        <div class="text-center py-6">
            <p class="font-serif text-5xl text-[#C8A96E] mb-2">{{ $stats['plantas_catalogo'] }}</p>
            <p class="text-[10px] uppercase tracking-widest text-[#7A8E72]">espécies catalogadas</p>
        </div>
        <a href="/admin/plantas/criar"
           class="block text-center text-[10px] uppercase tracking-widest bg-[#C8A96E]/10 border border-[#C8A96E]/20 text-[#C8A96E] hover:bg-[#C8A96E]/20 px-4 py-3 rounded-xl transition-all duration-150">
            + Adicionar espécie
        </a>
    </div>

</div>

@endsection
