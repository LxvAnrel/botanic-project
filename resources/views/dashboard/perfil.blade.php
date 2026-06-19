@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')

{{-- Sub-nav do dashboard --}}
<div class="sticky top-[72px] z-40 px-4 pb-2">
    <div class="max-w-7xl mx-auto">
        <div class="glass rounded-2xl px-2 py-1.5 flex gap-1 overflow-x-auto">
            <a href="{{ route('dashboard') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Diário
            </a>
            <a href="{{ route('alertas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Alertas
            </a>
            <a href="{{ route('conquistas') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Conquistas
            </a>
            <a href="{{ route('perfil') }}"
               class="shrink-0 flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
                Perfil
            </a>
        </div>
    </div>
</div>

<div class="max-w-lg mx-auto px-4 md:px-6 py-8">

    {{-- Avatar + nome --}}
    <div class="text-center mb-8">
        <div class="w-20 h-20 glass-gold rounded-full flex items-center justify-center mx-auto mb-4"
             style="box-shadow: 0 0 40px rgba(200,169,110,0.25);">
            <span class="font-serif text-3xl text-[#C8A96E]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        </div>
        <h1 class="font-serif font-light text-2xl text-[#EDE0CC]">{{ auth()->user()->name }}</h1>
        <p class="text-[#3A5E2D] text-xs uppercase tracking-wider mt-1">Membro desde {{ auth()->user()->created_at->format('M Y') }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="glass-gold rounded-2xl p-5 text-center">
            <p class="font-serif text-3xl text-[#C8A96E]" style="text-shadow:0 0 20px rgba(200,169,110,0.4)">
                {{ auth()->user()->plants()->count() }}
            </p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Plantas no diário</p>
        </div>
        <div class="glass rounded-2xl p-5 text-center">
            @php $naoLidas = auth()->user()->unreadNotifications()->count(); @endphp
            <p class="font-serif text-3xl {{ $naoLidas > 0 ? 'text-[#C8A96E]' : 'text-[#3A5E2D]' }}">{{ $naoLidas }}</p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Não lidas</p>
        </div>
    </div>

    {{-- Dados --}}
    <div class="glass rounded-2xl p-6 space-y-4 mb-4">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Informações</p>
        <div class="border-t border-white/[0.06] pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D]">Nome</p>
                <p class="text-[#EDE0CC] text-sm">{{ auth()->user()->name }}</p>
            </div>
            <div class="flex items-center justify-between gap-4">
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] shrink-0">Email</p>
                <p class="text-[#EDE0CC] text-sm truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    {{-- Card de notificações --}}
    <div class="glass rounded-2xl p-6 mb-4 space-y-5">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Notificações</p>

        {{-- Email --}}
        <div class="border-t border-white/[0.06] pt-5">
            <div class="flex items-start gap-3">
                <div class="shrink-0 w-9 h-9 bg-[#C8A96E]/10 rounded-full flex items-center justify-center text-base mt-0.5">✉</div>
                <div>
                    <p class="text-[#EDE0CC] text-sm font-medium">Alertas por e-mail</p>
                    <p class="text-[#7A8E72] text-xs leading-relaxed mt-1">
                        Enviamos alertas automáticos para <span class="text-[#EDE0CC]">{{ auth()->user()->email }}</span> quando chega a época de poda ou quando suas plantas precisam de rega ou adubação.
                    </p>
                    <span class="inline-block mt-2 text-[9px] uppercase tracking-wider text-[#3A5E2D] border border-[#3A5E2D]/40 px-2 py-0.5 rounded-full">Ativo · automático</span>
                </div>
            </div>
        </div>

        {{-- Push --}}
        <div class="border-t border-white/[0.06] pt-5">
            <div class="flex items-start gap-3">
                <div class="shrink-0 w-9 h-9 bg-[#C8A96E]/10 rounded-full flex items-center justify-center text-base mt-0.5">🔔</div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-3 mb-1">
                        <p class="text-[#EDE0CC] text-sm font-medium">Notificações push</p>
                        <button id="push-toggle" type="button" onclick="floraTogglePush()"
                                class="shrink-0 text-[9px] uppercase tracking-widest px-4 py-1.5 rounded-full border transition-all duration-200 glass border-white/[0.07] text-[#7A8E72] disabled:opacity-40">
                            …
                        </button>
                    </div>
                    <p id="push-status" class="text-[#7A8E72] text-xs leading-relaxed">Verificando…</p>
                    <p id="push-denied" class="text-[10px] text-amber-400/70 mt-2 leading-relaxed" style="display:none;">
                        Notificações bloqueadas pelo navegador. Siga o tutorial abaixo para habilitar manualmente.
                    </p>
                    <p id="push-error" class="text-[10px] text-red-400/80 mt-2 leading-relaxed" style="display:none;"></p>
                </div>
            </div>

            {{-- Tutorial --}}
            <div class="glass rounded-xl p-4 mt-4 space-y-4">
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Como ativar no celular</p>

                <div>
                    <p class="text-[10px] uppercase tracking-wider text-[#7A8E72] mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 bg-[#7A8E72]/15 rounded-full flex items-center justify-center text-[9px] font-bold">A</span>
                        Android · Chrome
                    </p>
                    <ol class="space-y-1.5 pl-7">
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">1.</span>Toque em <span class="text-[#C8A96E]">"Ativar"</span> acima</li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">2.</span>Toque em <span class="text-[#C8A96E]">"Permitir"</span> quando o Chrome perguntar</li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">3.</span>Pronto — alertas chegam mesmo com o app fechado</li>
                    </ol>
                    <p class="text-[#3A5E2D] text-[10px] mt-2 pl-7">Se recusou antes: Chrome ⋮ → Configurações do site → Notificações → Flora → Permitir</p>
                </div>

                <div class="border-t border-white/[0.06] pt-4">
                    <p class="text-[10px] uppercase tracking-wider text-[#7A8E72] mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 bg-[#7A8E72]/15 rounded-full flex items-center justify-center text-[9px] font-bold">i</span>
                        iPhone · Safari (iOS 16.4+)
                    </p>
                    <ol class="space-y-1.5 pl-7">
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">1.</span>No Safari, toque no ícone de compartilhar <span class="text-[#C8A96E]">⬆</span></li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">2.</span>Toque em <span class="text-[#C8A96E]">"Adicionar à Tela de Início"</span></li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">3.</span>Abra o app instalado e toque em <span class="text-[#C8A96E]">"Ativar"</span></li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">4.</span>Toque em <span class="text-[#C8A96E]">"Permitir"</span> quando o Safari perguntar</li>
                    </ol>
                    <p class="text-[#3A5E2D] text-[10px] mt-2 pl-7">No iOS, push só funciona quando o site é adicionado à Tela de Início (PWA).</p>
                </div>

                <div class="border-t border-white/[0.06] pt-4">
                    <p class="text-[10px] uppercase tracking-wider text-[#7A8E72] mb-2 flex items-center gap-2">
                        <span class="w-5 h-5 bg-[#7A8E72]/15 rounded-full flex items-center justify-center text-[9px] font-bold">D</span>
                        Desktop · Chrome / Firefox / Edge
                    </p>
                    <ol class="space-y-1.5 pl-7">
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">1.</span>Toque em <span class="text-[#C8A96E]">"Ativar"</span> acima</li>
                        <li class="text-[#EDE0CC] text-xs flex gap-2"><span class="text-[#3A5E2D] shrink-0">2.</span>Clique em <span class="text-[#C8A96E]">"Permitir"</span> na janela do navegador</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Tipos de alerta --}}
        <div class="border-t border-white/[0.06] pt-5">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mb-4">Você recebe alertas de</p>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-base w-6 text-center">✂</span>
                    <div>
                        <p class="text-[#EDE0CC] text-xs">Época de poda</p>
                        <p class="text-[#3A5E2D] text-[10px]">Uma vez por estação, quando sua planta deve ser podada</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-base w-6 text-center">💧</span>
                    <div>
                        <p class="text-[#EDE0CC] text-xs">Rega atrasada</p>
                        <p class="text-[#3A5E2D] text-[10px]">Quando passa do intervalo ideal sem rega registrada no Diário</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-base w-6 text-center">🌱</span>
                    <div>
                        <p class="text-[#EDE0CC] text-xs">Adubação atrasada</p>
                        <p class="text-[#3A5E2D] text-[10px]">Quando passa mais de 30 dias sem adubação registrada no Diário</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ações --}}
    <div class="space-y-2">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center justify-center gap-2 glass border border-white/[0.07] text-[#7A8E72] hover:text-[#C8A96E] hover:border-[#C8A96E]/30 text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Editar perfil
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 glass border border-red-900/20 text-red-400/60 hover:text-red-400 hover:border-red-400/30 text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sair da conta
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    function deviceLabel() {
        return window.Flora && window.Flora.push ? window.Flora.push.deviceLabel() : 'dispositivo';
    }

    function renderPush(state, errorMsg) {
        var btn    = document.getElementById('push-toggle');
        var status = document.getElementById('push-status');
        var denied = document.getElementById('push-denied');
        var errBox = document.getElementById('push-error');
        btn.disabled = false;
        denied.style.display = 'none';
        if (errBox) errBox.style.display = 'none';

        if (!window.Flora || !window.Flora.push || !window.Flora.push.supported()) {
            status.textContent = 'Não suportado neste navegador';
            btn.style.display = 'none';
            return;
        }
        if (typeof Notification !== 'undefined' && Notification.permission === 'denied') {
            status.textContent = 'Bloqueadas pelo navegador';
            btn.style.display = 'none';
            denied.style.display = 'block';
            return;
        }

        if (errorMsg && errBox) {
            errBox.textContent = 'Erro: ' + errorMsg;
            errBox.style.display = 'block';
        }

        if (state === 'on') {
            status.textContent = 'Ativas neste ' + deviceLabel();
            btn.textContent = 'Desativar';
            btn.classList.add('text-[#C8A96E]', 'border-[#C8A96E]/40', 'bg-[#C8A96E]/10');
            btn.classList.remove('text-[#7A8E72]', 'border-white/[0.07]');
        } else {
            status.textContent = 'Inativas neste ' + deviceLabel() + ' — clique para ativar';
            btn.textContent = 'Ativar';
            btn.classList.remove('text-[#C8A96E]', 'border-[#C8A96E]/40', 'bg-[#C8A96E]/10');
            btn.classList.add('text-[#7A8E72]', 'border-white/[0.07]');
        }
        btn.dataset.state = state;
    }

    window.floraTogglePush = async function () {
        var btn = document.getElementById('push-toggle');
        btn.disabled = true;
        btn.textContent = '…';
        if (btn.dataset.state === 'on') {
            await window.Flora.push.unsubscribe();
            renderPush('off');
        } else {
            var result = await window.Flora.push.subscribe();
            if (result.error === 'denied') {
                var denied = document.getElementById('push-denied');
                if (denied) denied.style.display = 'block';
                renderPush('off');
            } else {
                renderPush(result.ok ? 'on' : 'off', result.ok ? null : result.error);
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(async function () {
            if (window.Flora && window.Flora.push && window.Flora.push.supported()) {
                var on = await window.Flora.push.isSubscribed();
                renderPush(on ? 'on' : 'off');
            } else {
                renderPush('off');
            }
        }, 300);
    });
})();
</script>

@endsection
