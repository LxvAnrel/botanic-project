@extends('layouts.app')

@section('title', 'Alertas de Poda')

@section('content')

{{-- Sub-nav do dashboard --}}
<div class="sticky top-[72px] z-40 px-4 pb-2">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-2xl px-2 py-1.5 flex gap-1">
            <a href="{{ route('dashboard') }}"
               class="flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Diário
            </a>
            <a href="{{ route('alertas') }}"
               class="flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
                Alertas
            </a>
            <a href="{{ route('perfil') }}"
               class="flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Perfil
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-10 py-8">

    <div class="mb-8 pb-6 border-b border-white/[0.06]">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-2">— Cuidados sazonais</p>
        <h1 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC]">Alertas de Poda</h1>
    </div>

    @if($notificacoes->count() > 0)
        <div class="space-y-2">
            @foreach($notificacoes as $notif)
            <div class="glass rounded-2xl p-5 md:p-6 flex gap-4 items-start">

                {{-- Ícone sazonal --}}
                <div class="shrink-0 w-10 h-10 glass-gold rounded-full flex items-center justify-center text-base">
                    @php
                        $titulo = strtolower($notif->data['titulo'] ?? '');
                        $icon = str_contains($titulo,'verão') ? '☀' : (str_contains($titulo,'outono') ? '🍂' : (str_contains($titulo,'inverno') ? '❄' : '🌸'));
                    @endphp
                    {{ $icon }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                        <p class="text-[10px] uppercase tracking-[0.3em] text-[#C8A96E]">
                            {{ $notif->data['titulo'] ?? 'Notificação' }}
                        </p>
                        <span class="text-[#3A5E2D] text-[10px] shrink-0">{{ $notif->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-[#EDE0CC] text-sm leading-relaxed">{{ $notif->data['mensagem'] ?? '' }}</p>
                    @if(!empty($notif->data['planta_nome']))
                    <p class="text-[#3A5E2D] text-[10px] mt-2 uppercase tracking-wider">
                        Planta: <span class="text-[#7A8E72]">{{ $notif->data['planta_nome'] }}</span>
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($notificacoes->hasPages())
        <div class="mt-8 pt-6 border-t border-white/[0.06]">
            {{ $notificacoes->links() }}
        </div>
        @endif

    @else
        <div class="glass rounded-3xl p-16 md:p-20 text-center">
            <div class="w-16 h-16 glass rounded-full flex items-center justify-center mx-auto mb-5 text-3xl">✓</div>
            <p class="font-serif font-light text-xl md:text-2xl text-[#EDE0CC] mb-2">Tudo em dia</p>
            <p class="text-[#7A8E72] text-sm max-w-xs mx-auto">Nenhuma notificação no momento. Suas plantas estão bem cuidadas.</p>
        </div>
    @endif
</div>
@endsection
