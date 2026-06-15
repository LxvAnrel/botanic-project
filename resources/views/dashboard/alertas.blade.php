@extends('layouts.app')

@section('title', 'Alertas de Poda')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">

    <div class="mb-10 border-b border-white/[0.06] pb-8">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-3">— Cuidados sazonais</p>
        <h1 class="font-serif font-light text-5xl text-[#EDE0CC]">Alertas de Poda</h1>
        <p class="text-[#7A8E72] text-sm mt-2">Histórico de avisos sobre poda e cuidados das suas plantas.</p>
    </div>

    @if($notificacoes->count() > 0)
        <div class="space-y-3">
            @foreach($notificacoes as $notif)
                <div class="glass rounded-2xl p-6 flex justify-between items-start gap-6 border-l-2 border-[#C8A96E]/40">
                    <div class="flex-1">
                        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-2">
                            {{ $notif->data['titulo'] ?? 'Notificação' }}
                        </p>
                        <p class="text-[#EDE0CC] text-sm leading-relaxed">{{ $notif->data['mensagem'] ?? '' }}</p>
                        <p class="text-[#3A5E2D] text-xs mt-2 uppercase tracking-wider">
                            Planta: <span class="text-[#7A8E72]">{{ $notif->data['planta_nome'] ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <span class="text-[#3A5E2D] text-xs flex-shrink-0">{{ $notif->created_at->format('d/m/Y H:i') }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $notificacoes->links() }}</div>

    @else
        <div class="glass rounded-3xl p-20 text-center">
            <p class="font-serif text-5xl text-[#22381B] mb-4">✓</p>
            <p class="font-serif font-light text-2xl text-[#EDE0CC] mb-2">Tudo em dia</p>
            <p class="text-[#7A8E72] text-sm">Nenhuma notificação no momento. Suas plantas estão bem cuidadas.</p>
        </div>
    @endif
</div>
@endsection
