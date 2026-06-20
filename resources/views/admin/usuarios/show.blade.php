@extends('admin.layout')
@section('title', $user->nickname ?? $user->name)
@section('breadcrumb', 'Usuários')
@section('back_url', '/admin/usuarios')
@section('back_label', 'Usuários')

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
                <h2 class="text-[#EDE0CC] font-medium">{{ $user->nickname ?? $user->name }}</h2>
                @if($user->nickname)<p class="text-[#3A5E2D] text-[10px]">{{ $user->name }}</p>@endif
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

            {{-- Impersonação (mesma sessão + barra de retorno) --}}
            <form method="POST" action="/admin/usuarios/{{ $user->id }}/impersonar">
                @csrf
                <button class="w-full flex items-center gap-3 text-xs uppercase tracking-widest
                               text-[#C8A96E] hover:text-[#D4BA8A] px-3 py-2.5 rounded-xl hover:bg-[#C8A96E]/8 transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Entrar como este usuário
                </button>
            </form>

            {{-- Preview isolado (nova aba, token temporário) --}}
            <button type="button" id="btn-preview-token"
                    data-url="{{ route('admin.preview-token', $user) }}"
                    class="w-full flex items-center gap-3 text-xs uppercase tracking-widest
                           text-violet-400/80 hover:text-violet-400 px-3 py-2.5 rounded-xl hover:bg-violet-500/8 transition-all">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span id="btn-preview-label">Preview em nova aba</span>
                <span class="ml-auto text-[8px] text-violet-400/50 border border-violet-400/20 px-1.5 py-0.5 rounded-full">30 min</span>
            </button>

            <div class="border-t border-white/[0.06] my-1"></div>

            <form method="POST" action="/admin/usuarios/{{ $user->id }}/banir"
                  data-name="{{ $user->nickname ?? $user->name }}"
                  onsubmit="return confirm('Remover a conta de ' + this.dataset.name + '? Ação irreversível.')">
                @csrf
                @method('DELETE')
                <button class="w-full flex items-center gap-3 text-xs uppercase tracking-widest
                               text-red-400/70 hover:text-red-400 px-3 py-2.5 rounded-xl hover:bg-red-900/10 transition-all">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Remover conta
                </button>
            </form>
        </div>

        <script>
        (function () {
            var btn   = document.getElementById('btn-preview-token');
            var label = document.getElementById('btn-preview-label');
            if (!btn) return;

            btn.addEventListener('click', function () {
                label.textContent = 'Gerando token…';
                btn.disabled = true;

                var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    window.open(data.url, '_blank');
                    label.textContent = 'Preview em nova aba';
                    btn.disabled = false;
                })
                .catch(function () {
                    label.textContent = 'Erro — tente novamente';
                    btn.disabled = false;
                });
            });
        })();
        </script>
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
