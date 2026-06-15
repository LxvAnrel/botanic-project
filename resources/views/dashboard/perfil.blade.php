@extends('layouts.app')

@section('title', 'Meu Perfil')

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
               class="flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5">
                Alertas
            </a>
            <a href="{{ route('perfil') }}"
               class="flex-1 text-center text-[10px] uppercase tracking-widest font-medium px-4 py-2.5 rounded-xl transition-all duration-200 bg-white/[0.07] text-[#C8A96E]">
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
            <p class="font-serif text-3xl text-[#C8A96E]">{{ auth()->user()->notifications()->count() }}</p>
            <p class="text-[9px] uppercase tracking-[0.25em] text-[#7A8E72] mt-1">Notificações</p>
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

    {{-- Notificações push --}}
    <div class="glass rounded-2xl p-6 mb-4" id="push-card">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-1">Notificações</p>
                <p class="text-[#EDE0CC] text-sm">Alertas de poda no celular</p>
                <p class="text-[#7A8E72] text-xs mt-1" id="push-status">Verificando…</p>
            </div>
            <button type="button" id="push-toggle"
                    onclick="floraTogglePush()"
                    class="shrink-0 glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-[10px] uppercase tracking-widest px-4 py-2.5 rounded-full transition-all duration-200 disabled:opacity-40">
                …
            </button>
        </div>
        <p class="text-[#3A5E2D] text-[10px] mt-3 leading-relaxed" id="push-hint" style="display:none;">
            Seu navegador bloqueou as notificações. Permita-as nas configurações do site para ativar.
        </p>
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
function floraRenderPush(state) {
    var status = document.getElementById('push-status');
    var btn = document.getElementById('push-toggle');
    var hint = document.getElementById('push-hint');
    btn.disabled = false;
    hint.style.display = 'none';

    if (!window.Flora || !window.Flora.push || !window.Flora.push.supported()) {
        status.textContent = 'Não suportado neste navegador';
        btn.style.display = 'none';
        return;
    }
    if (typeof Notification !== 'undefined' && Notification.permission === 'denied') {
        status.textContent = 'Bloqueadas pelo navegador';
        btn.style.display = 'none';
        hint.style.display = 'block';
        return;
    }
    if (state === 'on') {
        status.textContent = 'Ativadas neste dispositivo';
        btn.textContent = 'Desativar';
    } else {
        status.textContent = 'Desativadas';
        btn.textContent = 'Ativar';
    }
    btn.dataset.state = state;
}

async function floraTogglePush() {
    var btn = document.getElementById('push-toggle');
    btn.disabled = true;
    btn.textContent = '…';
    if (btn.dataset.state === 'on') {
        await window.Flora.push.unsubscribe();
        floraRenderPush('off');
    } else {
        var ok = await window.Flora.push.subscribe();
        floraRenderPush(ok ? 'on' : 'off');
    }
}

document.addEventListener('DOMContentLoaded', async function () {
    if (window.Flora && window.Flora.push && window.Flora.push.supported()) {
        var on = await window.Flora.push.isSubscribed();
        floraRenderPush(on ? 'on' : 'off');
    } else {
        floraRenderPush('off');
    }
});
</script>
@endsection
