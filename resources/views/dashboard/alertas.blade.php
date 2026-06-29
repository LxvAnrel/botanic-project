@extends('layouts.app')

@section('title', 'Alertas')

@section('content')

{{-- Navegacao entre as secoes do dashboard --}}
<div class="sticky top-[72px] z-40 px-4 pb-2">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-2xl px-2 py-1.5 flex gap-1 overflow-x-auto">
            <a href="{{ route('dashboard') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Diário
            </a>
            <a href="{{ route('alertas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
                Alertas
                @if($totalNaoLidas > 0)
                    <span class="inline-flex items-center justify-center w-4 h-4 ml-1 text-[9px] bg-[#C8A96E] text-[#0E1A0B] rounded-full font-semibold">{{ $totalNaoLidas > 9 ? '9+' : $totalNaoLidas }}</span>
                @endif
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

<div class="max-w-3xl mx-auto px-4 md:px-6 py-8">

    {{-- Titulo e botao de marcar todas como lidas --}}
    <div class="flex items-end justify-between gap-4 mb-6 pb-6 border-b border-white/[0.06]">
        <div>
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-2">— Cuidados das suas plantas</p>
            <h1 class="font-serif font-light text-3xl md:text-4xl text-[#EDE0CC]">
                Alertas
                @if($totalNaoLidas > 0)
                    <span class="text-[#C8A96E] text-2xl">({{ $totalNaoLidas }})</span>
                @endif
            </h1>
        </div>
        @if($totalNaoLidas > 0)
        <form method="POST" action="{{ route('alertas.markAllRead') }}" class="shrink-0">
            @csrf
            <button type="submit"
                    class="text-[10px] uppercase tracking-widest text-[#7A8E72] hover:text-[#C8A96E] glass border border-white/[0.07] hover:border-[#C8A96E]/30 px-4 py-2 rounded-full transition-all duration-200">
                Marcar todas como lidas
            </button>
        </form>
        @endif
    </div>

    {{-- Abas para filtrar por tipo de alerta --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        @php
            $tabs = ['todas' => 'Todas', 'poda' => '✂ Poda', 'cuidados' => '💧 Cuidados'];
        @endphp
        @foreach($tabs as $key => $label)
        <a href="{{ route('alertas', ['filtro' => $key]) }}"
           class="shrink-0 text-[10px] uppercase tracking-widest px-5 py-2 rounded-full border transition-all duration-200
                  {{ $filtro === $key
                        ? 'bg-[#C8A96E]/15 border-[#C8A96E]/40 text-[#C8A96E]'
                        : 'glass border-white/[0.07] text-[#7A8E72] hover:border-[#C8A96E]/30 hover:text-[#C8A96E]' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Lista de alertas e notificacoes --}}
    @if($notificacoes->count() > 0)
        <div class="space-y-2">
            @foreach($notificacoes as $notif)
            @php
                $isUnread = is_null($notif->read_at);
                $tipoClass = class_basename($notif->type);
                $dados = $notif->data;

                if ($tipoClass === 'PruningSeasonNotification') {
                    $icon  = '✂';
                    $label = 'Poda';
                    $cor   = 'text-[#C8A96E]';
                    $corBg = 'bg-[#C8A96E]/10';
                } elseif (($dados['tipo'] ?? '') === 'rega') {
                    $icon  = '💧';
                    $label = 'Rega';
                    $cor   = 'text-blue-400';
                    $corBg = 'bg-blue-400/10';
                } elseif (($dados['tipo'] ?? '') === 'adubacao') {
                    $icon  = '🌱';
                    $label = 'Adubação';
                    $cor   = 'text-[#7A8E72]';
                    $corBg = 'bg-[#7A8E72]/10';
                } else {
                    $icon  = '🔔';
                    $label = 'Aviso';
                    $cor   = 'text-[#EDE0CC]';
                    $corBg = 'bg-white/5';
                }
            @endphp

            <div class="glass rounded-2xl p-4 md:p-5 flex gap-4 items-start relative transition-all duration-200
                        {{ $isUnread ? 'border border-white/[0.12]' : 'opacity-60' }}">

                @if($isUnread)
                <div class="absolute top-4 right-4 w-2 h-2 rounded-full bg-[#C8A96E]" title="Não lida"></div>
                @endif

                {{-- Ícone --}}
                <div class="shrink-0 w-10 h-10 {{ $corBg }} rounded-full flex items-center justify-center text-base">
                    {{ $icon }}
                </div>

                <div class="flex-1 min-w-0 pr-4">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[9px] uppercase tracking-[0.3em] {{ $cor }} font-medium">{{ $label }}</span>
                        @if(!empty($dados['planta_nome']))
                            <span class="text-[#3A5E2D] text-[9px]">· {{ $dados['planta_nome'] }}</span>
                        @endif
                        <span class="text-[#3A5E2D] text-[9px] ml-auto">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>

                    <p class="text-[#EDE0CC] text-sm font-medium leading-snug mb-0.5">
                        {{ $dados['titulo'] ?? ($dados['mensagem'] ?? 'Notificação') }}
                    </p>

                    @if(!empty($dados['titulo']) && !empty($dados['mensagem']))
                    <p class="text-[#7A8E72] text-xs leading-relaxed">{{ $dados['mensagem'] }}</p>
                    @endif

                    {{-- Ações --}}
                    <div class="flex items-center gap-4 mt-3">
                        @if($isUnread)
                        <form method="POST" action="{{ route('alertas.markRead', $notif->id) }}">
                            @csrf
                            <button type="submit" class="text-[9px] uppercase tracking-wider text-[#7A8E72] hover:text-[#C8A96E] transition-colors">
                                Marcar como lida
                            </button>
                        </form>
                        @else
                        <span class="text-[9px] uppercase tracking-wider text-[#3A5E2D]">Lida</span>
                        @endif

                        <form method="POST" action="{{ route('alertas.destroy', $notif->id) }}" onsubmit="return confirm('Descartar este alerta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[9px] uppercase tracking-wider text-[#3A5E2D] hover:text-red-400/70 transition-colors">
                                Descartar
                            </button>
                        </form>
                    </div>
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
            <div class="w-16 h-16 glass rounded-full flex items-center justify-center mx-auto mb-5 text-2xl">✓</div>
            <p class="font-serif font-light text-xl md:text-2xl text-[#EDE0CC] mb-2">Tudo em dia</p>
            <p class="text-[#7A8E72] text-sm max-w-xs mx-auto">
                @if($filtro !== 'todas')
                    Nenhum alerta nesta categoria no momento.
                @else
                    Nenhuma notificação. Suas plantas estão bem cuidadas.
                @endif
            </p>
            @if($filtro !== 'todas')
            <a href="{{ route('alertas') }}" class="inline-block mt-6 text-[10px] uppercase tracking-widest text-[#C8A96E] hover:underline">
                Ver todas
            </a>
            @endif
        </div>
    @endif
</div>
@endsection
