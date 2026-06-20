@extends('admin.layout')
@section('title', 'Sistema')

@section('content')

<div class="space-y-6">
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-6">Comandos do Scheduler</h2>
        <div class="space-y-3">
            @foreach($comandos as $cmd => $descricao)
            @php $ultima = $ultimasExecucoes[$cmd] ?? null; @endphp
            <div class="flex items-center justify-between py-3 border-b border-white/[0.05] last:border-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-[#EDE0CC]">{{ $descricao }}</p>
                    <p class="text-[10px] text-[#3A5E2D] font-mono mt-0.5">{{ $cmd }}</p>
                    @if($ultima)
                    <p class="text-[10px] text-[#7A8E72] mt-1">Última execução: {{ \Carbon\Carbon::parse($ultima['at'])->diffForHumans() }}</p>
                    @else
                    <p class="text-[10px] text-[#3A5E2D] mt-1">Nunca executado via painel</p>
                    @endif
                </div>
                <form method="POST" action="/admin/sistema/rodar/{{ urlencode($cmd) }}" class="ml-4 shrink-0">
                    @csrf
                    <button class="glass border border-[#C8A96E]/20 text-[#C8A96E] text-[9px] uppercase tracking-widest px-4 py-2 rounded-xl hover:border-[#C8A96E]/50 transition-all">
                        ▶ Rodar agora
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Schedule --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Horários configurados</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            @php
            $schedule = [
                ['cmd' => 'plants:check-care',             'horarios' => '07:00 · 12:00 · 17:00 · 21:00'],
                ['cmd' => 'streak:check-at-risk',          'horarios' => '09:00 · 13:00 · 18:00 · 22:00'],
                ['cmd' => 'flora:first-annotation-emails', 'horarios' => '06:00 · 11:00 · 16:00 · 23:00'],
                ['cmd' => 'plants:check-pruning-season',   'horarios' => '08:00 (diário)'],
                ['cmd' => 'accounts:purge',                'horarios' => '03:00 (diário)'],
            ];
            @endphp
            @foreach($schedule as $s)
            <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-4">
                <p class="font-mono text-[#C8A96E] text-[10px] mb-1">{{ $s['cmd'] }}</p>
                <p class="text-[#7A8E72]">{{ $s['horarios'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Info do sistema --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Informações</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div><p class="text-[#3A5E2D] mb-1">PHP</p><p class="text-[#EDE0CC]">{{ PHP_VERSION }}</p></div>
            <div><p class="text-[#3A5E2D] mb-1">Laravel</p><p class="text-[#EDE0CC]">{{ app()->version() }}</p></div>
            <div><p class="text-[#3A5E2D] mb-1">Ambiente</p><p class="text-[#EDE0CC]">{{ app()->environment() }}</p></div>
            <div><p class="text-[#3A5E2D] mb-1">Timezone</p><p class="text-[#EDE0CC]">{{ config('app.timezone') }}</p></div>
        </div>
    </div>

    {{-- Rota de debug (movida aqui) --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-3">Debug rápido</h2>
        <p class="text-xs text-[#7A8E72] mb-4">Roda o check-care e exibe o output completo em tempo real.</p>
        <a href="/admin/debug/check-care"
           class="inline-block glass border border-white/[0.08] text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase tracking-widest px-5 py-2.5 rounded-xl transition-all">
            Abrir output de debug
        </a>
    </div>
</div>

@endsection
