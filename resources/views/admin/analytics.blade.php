@extends('admin.layout')
@section('title', 'Analytics')

@section('content')

{{-- Funil de onboarding --}}
<div class="glass rounded-2xl p-6 mb-6">
    <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-6">Funil de onboarding</h2>
    <div class="grid grid-cols-3 gap-2 md:gap-4">
        @php
        $funil = [
            ['label' => 'Cadastrou', 'value' => $totalUsuarios, 'pct' => 100, 'cor' => '#C8A96E'],
            ['label' => 'Adicionou planta', 'value' => $comPlanta, 'pct' => $totalUsuarios ? round($comPlanta / $totalUsuarios * 100) : 0, 'cor' => '#7AC77A'],
            ['label' => 'Registrou cuidado', 'value' => $comCuidado, 'pct' => $totalUsuarios ? round($comCuidado / $totalUsuarios * 100) : 0, 'cor' => '#7A8E72'],
        ];
        @endphp
        @foreach($funil as $f)
        <div class="text-center">
            <p class="font-serif text-2xl md:text-4xl mb-1" style="color:{{ $f['cor'] }}">{{ $f['value'] }}</p>
            <p class="text-xs md:text-sm text-[#EDE0CC] mb-1">{{ $f['label'] }}</p>
            <div class="h-1.5 bg-white/[0.06] rounded-full overflow-hidden mx-auto max-w-[120px]">
                <div class="h-full rounded-full" style="width:{{ $f['pct'] }}%; background:{{ $f['cor'] }};"></div>
            </div>
            <p class="text-[10px] text-[#3A5E2D] mt-1">{{ $f['pct'] }}%</p>
        </div>
        @endforeach
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Retenção --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Retenção por atividade</h2>
        <div class="space-y-4">
            @php
            $retencao = [
                ['label' => 'Ativos nos últimos 7d',  'value' => $ativos7d,  'total' => $totalUsuarios],
                ['label' => 'Ativos nos últimos 30d', 'value' => $ativos30d, 'total' => $totalUsuarios],
            ];
            @endphp
            @foreach($retencao as $r)
            @php $pct = $r['total'] ? round($r['value'] / $r['total'] * 100) : 0; @endphp
            <div>
                <div class="flex justify-between mb-1.5">
                    <span class="text-xs text-[#EDE0CC]">{{ $r['label'] }}</span>
                    <span class="text-xs text-[#C8A96E]">{{ $r['value'] }} <span class="text-[#3A5E2D]">({{ $pct }}%)</span></span>
                </div>
                <div class="h-2 bg-white/[0.06] rounded-full overflow-hidden">
                    <div class="h-full bg-[#C8A96E] rounded-full transition-all" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Plantas populares --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Plantas mais populares</h2>
        <div class="space-y-2.5">
            @php $max = $plantasPopulares->first()?->users_count ?? 1; @endphp
            @foreach($plantasPopulares as $i => $p)
            <div class="flex items-center gap-3">
                <span class="text-[10px] text-[#3A5E2D] w-4 shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs text-[#EDE0CC] truncate">{{ $p->nome_popular }}</span>
                        <span class="text-xs text-[#C8A96E] ml-2 shrink-0">{{ $p->users_count }}</span>
                    </div>
                    <div class="h-1 bg-white/[0.06] rounded-full overflow-hidden">
                        <div class="h-full bg-[#C8A96E]/60 rounded-full" style="width:{{ $max ? round($p->users_count / $max * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Novos usuários por dia --}}
<div class="glass rounded-2xl p-6 mb-6">
    <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Novos usuários — últimos 14 dias</h2>
    @if($novosPorDia->isEmpty())
        <p class="text-[#3A5E2D] text-sm">Sem dados.</p>
    @else
    @php $maxNovos = $novosPorDia->max('total') ?: 1; @endphp
    <div class="flex items-end gap-1.5 h-24">
        @foreach($novosPorDia as $dia)
        @php $h = round($dia->total / $maxNovos * 100); @endphp
        <div class="flex-1 flex flex-col items-center gap-1 group">
            <span class="text-[8px] text-[#C8A96E] opacity-0 group-hover:opacity-100 transition-opacity">{{ $dia->total }}</span>
            <div class="w-full bg-[#C8A96E]/60 rounded-t" style="height:{{ max(4, $h) }}%"></div>
            <span class="text-[7px] text-[#3A5E2D] rotate-45 origin-left mt-1">{{ \Carbon\Carbon::parse($dia->dia)->format('d/m') }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Cuidados por dia --}}
<div class="glass rounded-2xl p-6">
    <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Cuidados registrados — últimos 14 dias</h2>
    @if($cuidadosPorDia->isEmpty())
        <p class="text-[#3A5E2D] text-sm">Sem dados.</p>
    @else
    @php $maxCuidados = $cuidadosPorDia->max('total') ?: 1; @endphp
    <div class="flex items-end gap-1.5 h-24">
        @foreach($cuidadosPorDia as $dia)
        @php $h = round($dia->total / $maxCuidados * 100); @endphp
        <div class="flex-1 flex flex-col items-center gap-1 group">
            <span class="text-[8px] text-[#7AC77A] opacity-0 group-hover:opacity-100 transition-opacity">{{ $dia->total }}</span>
            <div class="w-full bg-[#7AC77A]/50 rounded-t" style="height:{{ max(4, $h) }}%"></div>
            <span class="text-[7px] text-[#3A5E2D] rotate-45 origin-left mt-1">{{ \Carbon\Carbon::parse($dia->dia)->format('d/m') }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
