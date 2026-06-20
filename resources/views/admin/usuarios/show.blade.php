@extends('admin.layout')
@section('title', $user->name)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Perfil --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="glass rounded-2xl p-6">
            <div class="flex flex-col items-center text-center mb-5">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full object-cover border-2 border-[#C8A96E]/30 mb-3">
                @else
                    <div class="w-16 h-16 rounded-full bg-[#C8A96E] flex items-center justify-center text-[#0B160A] font-bold text-xl mb-3">
                        {{ mb_strtoupper(mb_substr($user->name,0,1)) }}
                    </div>
                @endif
                <h2 class="text-[#EDE0CC] font-medium">{{ $user->name }}</h2>
                <p class="text-[#3A5E2D] text-xs mt-0.5">{{ $user->email }}</p>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">Cadastro</span>
                    <span class="text-[#EDE0CC]">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">XP</span>
                    <span class="text-[#C8A96E]">{{ $user->xp ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">Streak</span>
                    <span class="text-[#EDE0CC]">{{ $user->streak_days ?? 0 }} dias</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">Plantas</span>
                    <span class="text-[#EDE0CC]">{{ $user->plants->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">Email notif.</span>
                    <span class="{{ $user->email_notifications ? 'text-[#7AC77A]' : 'text-[#7A8E72]' }}">{{ $user->email_notifications ? 'Ativo' : 'Inativo' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#7A8E72]">Perfil público</span>
                    <span class="{{ $user->profile_public ? 'text-[#7AC77A]' : 'text-[#7A8E72]' }}">{{ $user->profile_public ? 'Sim' : 'Não' }}</span>
                </div>
            </div>
        </div>

        {{-- Ações --}}
        <div class="glass rounded-2xl p-5 space-y-2">
            <p class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-3">Ações</p>
            <form method="POST" action="/admin/usuarios/{{ $user->id }}/impersonar">
                @csrf
                <button class="w-full text-left text-xs uppercase tracking-widest text-[#C8A96E] hover:text-[#D4BA8A] px-3 py-2.5 rounded-xl hover:bg-[#C8A96E]/8 transition-all">
                    ↪ Entrar como este usuário
                </button>
            </form>
            <form method="POST" action="/admin/usuarios/{{ $user->id }}/banir"
                  onsubmit="return confirm('Remover a conta de {{ $user->name }}? Ação irreversível.')">
                @csrf
                @method('DELETE')
                <button class="w-full text-left text-xs uppercase tracking-widest text-red-400/70 hover:text-red-400 px-3 py-2.5 rounded-xl hover:bg-red-900/10 transition-all">
                    ✕ Remover conta
                </button>
            </form>
        </div>
    </div>

    {{-- Detalhes --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Plantas --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-4">Plantas no diário</h3>
            @if($user->plants->isEmpty())
                <p class="text-[#3A5E2D] text-sm">Nenhuma planta adicionada.</p>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($user->plants as $p)
                <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-3 text-center">
                    <p class="text-xs text-[#EDE0CC] mb-0.5">{{ $p->nome_popular }}</p>
                    <p class="text-[10px] text-[#3A5E2D] italic">{{ $p->nome_cientifico }}</p>
                    <p class="text-[9px] text-[#7A8E72] mt-1">desde {{ $p->pivot->created_at->format('d/m/Y') }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Últimas notificações --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-4">Últimas notificações</h3>
            @if($notificacoes->isEmpty())
                <p class="text-[#3A5E2D] text-sm">Nenhuma notificação.</p>
            @else
            <div class="space-y-2">
                @foreach($notificacoes as $n)
                <div class="flex items-start gap-3 py-2 border-b border-white/[0.04] last:border-0">
                    <div class="shrink-0 w-1.5 h-1.5 rounded-full mt-2 {{ $n->read_at ? 'bg-[#3A5E2D]' : 'bg-[#C8A96E]' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-[#EDE0CC] truncate">{{ $n->data['titulo'] ?? class_basename($n->type) }}</p>
                        <p class="text-[10px] text-[#3A5E2D]">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Últimos cuidados --}}
        <div class="glass rounded-2xl p-6">
            <h3 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-4">Histórico de cuidados</h3>
            @if($careLogs->isEmpty())
                <p class="text-[#3A5E2D] text-sm">Nenhum registro de cuidado.</p>
            @else
            <div class="space-y-2">
                @foreach($careLogs as $log)
                <div class="flex items-center justify-between py-1.5 border-b border-white/[0.04] last:border-0">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider {{ $log->tipo === 'rega' ? 'text-blue-400' : 'text-[#7A8E72]' }}">
                            {{ $log->tipo === 'rega' ? '💧' : '🌱' }} {{ $log->tipo }}
                        </span>
                        <span class="text-[10px] text-[#3A5E2D] ml-2">{{ $log->plant->nome_popular ?? '—' }}</span>
                    </div>
                    <span class="text-[10px] text-[#7A8E72]">{{ \Carbon\Carbon::parse($log->data)->format('d/m/Y') }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
