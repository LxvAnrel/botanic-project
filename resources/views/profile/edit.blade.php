@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')

{{-- Sub-nav --}}
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

<div class="max-w-lg mx-auto px-4 md:px-6 py-8 space-y-4">

    {{-- Cabeçalho --}}
    <div class="mb-6 pb-6 border-b border-white/[0.06]">
        <a href="{{ route('perfil') }}" class="inline-flex items-center gap-2 text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase tracking-widest mb-4 transition-colors">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar ao perfil
        </a>
        <h1 class="font-serif font-light text-3xl text-[#EDE0CC]">Editar perfil</h1>
    </div>

    {{-- ① Informações pessoais --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-5">Informações pessoais</p>

        @if(session('status') === 'profile-updated')
        <div class="flex items-center gap-2 glass-gold rounded-xl px-4 py-2.5 mb-5">
            <span class="text-[#C8A96E] text-sm">✓</span>
            <p class="text-[#C8A96E] text-xs uppercase tracking-wider">Perfil atualizado com sucesso</p>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Nome</label>
                <input id="name" name="name" type="text"
                       value="{{ old('name', $user->name) }}"
                       required autocomplete="name"
                       class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                @error('name')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">E-mail</label>
                <input id="email" name="email" type="email"
                       value="{{ old('email', $user->email) }}"
                       required autocomplete="email"
                       class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                @error('email')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200 mt-2">
                Salvar alterações
            </button>
        </form>
    </div>

    {{-- ② Alterar senha --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-5">Alterar senha</p>

        @if(session('status') === 'password-updated')
        <div class="flex items-center gap-2 glass-gold rounded-xl px-4 py-2.5 mb-5">
            <span class="text-[#C8A96E] text-sm">✓</span>
            <p class="text-[#C8A96E] text-xs uppercase tracking-wider">Senha atualizada com sucesso</p>
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Senha atual</label>
                <input id="current_password" name="current_password" type="password"
                       autocomplete="current-password"
                       class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                @error('current_password', 'updatePassword')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Nova senha</label>
                <input id="password" name="password" type="password"
                       autocomplete="new-password"
                       class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                @error('password', 'updatePassword')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Confirmar nova senha</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       autocomplete="new-password"
                       class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                @error('password_confirmation', 'updatePassword')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full glass border border-white/[0.07] hover:border-[#C8A96E]/30 text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200 mt-2">
                Alterar senha
            </button>
        </form>
    </div>

    {{-- ③ Apagar conta --}}
    <div class="glass rounded-2xl p-6 border border-red-900/10">
        <p class="text-[9px] uppercase tracking-[0.3em] text-red-400/60 mb-1">Zona de perigo</p>
        <p class="text-[#7A8E72] text-xs leading-relaxed mb-5">
            Ao apagar sua conta, todos os seus dados — diário de plantas, alertas e preferências — serão permanentemente removidos. Esta ação não pode ser desfeita.
        </p>

        {{-- Erros de exclusão --}}
        @if($errors->userDeletion->isNotEmpty())
        <div class="glass rounded-xl px-4 py-3 mb-4 border border-red-400/20">
            <p class="text-red-400/80 text-xs">{{ $errors->userDeletion->first('password') }}</p>
        </div>
        @endif

        {{-- Botão que revela o formulário --}}
        <button id="delete-reveal-btn" type="button" onclick="floraRevealDelete()"
                class="w-full flex items-center justify-center gap-2 glass border border-red-900/20 text-red-400/60 hover:text-red-400 hover:border-red-400/30 text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Apagar minha conta
        </button>

        {{-- Formulário de confirmação (oculto por padrão) --}}
        <div id="delete-confirm" style="display:none;" class="mt-4 space-y-4">
            <div class="glass rounded-xl px-4 py-3 border border-red-400/15">
                <p class="text-red-400/70 text-xs leading-relaxed">
                    Para confirmar, insira sua senha abaixo. Esta ação é irreversível.
                </p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="space-y-3">
                    <div>
                        <label for="delete_password" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Senha atual</label>
                        <input id="delete_password" name="password" type="password"
                               placeholder="Confirme com sua senha"
                               autocomplete="current-password"
                               class="w-full glass border border-red-900/20 focus:border-red-400/40 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="floraRevealDelete()"
                                class="flex-1 glass border border-white/[0.07] text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest py-3 rounded-full transition-all duration-200">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 bg-red-500/15 border border-red-400/30 text-red-400 hover:bg-red-500/25 hover:border-red-400/50 text-xs uppercase tracking-widest py-3 rounded-full transition-all duration-200">
                            Confirmar exclusão
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function floraRevealDelete() {
    var form = document.getElementById('delete-confirm');
    var btn  = document.getElementById('delete-reveal-btn');
    var open = form.style.display === 'block';
    form.style.display = open ? 'none' : 'block';
    btn.style.display  = open ? 'flex' : 'none';
    if (!open) document.getElementById('delete_password').focus();
}

// Abre automaticamente se houver erro de senha na exclusão
@if($errors->userDeletion->isNotEmpty())
document.addEventListener('DOMContentLoaded', function () { floraRevealDelete(); });
@endif
</script>

@endsection
