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

    {{-- ① Avatar --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-5">Foto de perfil</p>

        @if(session('status') === 'avatar-updated')
        <div class="flex items-center gap-2 glass-gold rounded-xl px-4 py-2.5 mb-5">
            <span class="text-[#C8A96E] text-sm">✓</span>
            <p class="text-[#C8A96E] text-xs uppercase tracking-wider">Foto atualizada com sucesso</p>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="flex items-center gap-5">
            @csrf

            {{-- Preview atual --}}
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" id="avatar-preview"
                     class="w-16 h-16 rounded-full object-cover border-2 border-[#C8A96E]/30 shrink-0">
            @else
                <div id="avatar-initials" class="w-16 h-16 rounded-full bg-[#C8A96E]/10 border-2 border-[#C8A96E]/30 flex items-center justify-center text-xl font-serif text-[#C8A96E] shrink-0">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
                <img id="avatar-preview" class="w-16 h-16 rounded-full object-cover border-2 border-[#C8A96E]/30 shrink-0 hidden">
            @endif

            <div class="flex-1 min-w-0">
                <label for="avatar" class="block glass border border-dashed border-white/20 hover:border-[#C8A96E]/40 rounded-xl px-4 py-3 cursor-pointer transition-all duration-200 text-center">
                    <span class="text-[#7A8E72] text-xs" id="avatar-label">Escolher imagem (JPG, PNG, WebP — máx. 2 MB)</span>
                    <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only"
                           onchange="previewAvatar(this)">
                </label>
                @error('avatar')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="glass-gold text-[#C8A96E] text-[10px] uppercase tracking-widest px-5 py-3 rounded-full transition-all duration-200 shrink-0">
                Salvar
            </button>
        </form>
    </div>

    {{-- ② Informações pessoais --}}
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

    {{-- ③ Perfil público --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-1">Perfil público &amp; Biografia</p>
        <p class="text-[#7A8E72] text-xs mb-5">Sua bio e conquistas aparecem no perfil público. Seu e-mail <strong class="text-[#EDE0CC]">nunca</strong> é compartilhado.</p>

        @if(session('status') === 'settings-updated')
        <div class="flex items-center gap-2 glass-gold rounded-xl px-4 py-2.5 mb-5">
            <span class="text-[#C8A96E] text-sm">✓</span>
            <p class="text-[#C8A96E] text-xs uppercase tracking-wider">Configurações salvas</p>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.settings') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="bio" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Bio <span class="text-[#3A5E2D]/50 normal-case tracking-normal">(até 280 caracteres)</span></label>
                <textarea id="bio" name="bio" rows="3" maxlength="280"
                          placeholder="Fale um pouco sobre você e suas plantas favoritas..."
                          class="w-full glass border border-white/[0.08] focus:border-[#C8A96E]/50 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200 resize-none">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                <p class="mt-1.5 text-xs text-red-400/80">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center justify-between gap-4 glass rounded-xl px-4 py-3.5 cursor-pointer hover:glass-gold transition-all duration-200">
                <div>
                    <p class="text-[#EDE0CC] text-sm">Perfil visível na comunidade</p>
                    <p class="text-[#7A8E72] text-[10px] mt-0.5">Apareça no ranking Top 10 dos mais engajados</p>
                </div>
                <div class="relative shrink-0">
                    <input type="checkbox" name="profile_public" id="profile_public" value="1" class="sr-only peer"
                           {{ old('profile_public', $user->profile_public) ? 'checked' : '' }}>
                    <div class="w-10 h-6 bg-white/10 peer-checked:bg-[#C8A96E]/70 rounded-full transition-colors duration-200"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white/60 peer-checked:bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-4"></div>
                </div>
            </label>

            @if($user->profile_public)
            <a href="{{ route('perfil.publico', $user) }}" class="block text-center text-[10px] text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">
                Ver meu perfil público →
            </a>
            @endif

            <button type="submit"
                    class="w-full glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200">
                Salvar configurações
            </button>
        </form>
    </div>

    {{-- ④ Alterar senha --}}
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

    {{-- ⑤ Notificações por e-mail --}}
    <div class="glass rounded-2xl p-6">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-1">E-mails de alertas</p>
        <p class="text-[#7A8E72] text-xs mb-5">Controle se deseja receber e-mails de cuidados de plantas, conquistas e avisos de sequência. E-mails essenciais de conta (exclusão, reativação) são sempre enviados.</p>

        @if(session('status') && !in_array(session('status'), ['profile-updated','password-updated']))
        <div class="flex items-center gap-2 glass-gold rounded-xl px-4 py-2.5 mb-5">
            <span class="text-[#C8A96E] text-sm">✓</span>
            <p class="text-[#C8A96E] text-xs">{{ session('status') }}</p>
        </div>
        @endif

        <form method="POST" action="{{ route('profile.email-notifications') }}" class="flex items-center justify-between gap-4">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-3">
                <span class="text-xl">{{ auth()->user()->email_notifications ? '🔔' : '🔕' }}</span>
                <div>
                    <p class="text-[#EDE0CC] text-sm">{{ auth()->user()->email_notifications ? 'E-mails ativados' : 'E-mails desativados' }}</p>
                    <p class="text-[#3A5E2D] text-[10px]">{{ auth()->user()->email_notifications ? 'Você recebe alertas de plantas e conquistas' : 'Você não recebe e-mails de alertas' }}</p>
                </div>
            </div>
            <button type="submit"
                    class="{{ auth()->user()->email_notifications ? 'glass border border-white/[0.07] text-[#7A8E72] hover:text-red-400' : 'glass-gold text-[#C8A96E]' }} text-[10px] uppercase tracking-widest px-5 py-2.5 rounded-full transition-all duration-200 shrink-0">
                {{ auth()->user()->email_notifications ? 'Desativar' : 'Reativar' }}
            </button>
        </form>
    </div>

    {{-- ⑥ Apagar conta --}}
    <div class="glass rounded-2xl p-6 border border-red-900/10">
        <p class="text-[9px] uppercase tracking-[0.3em] text-red-400/60 mb-3">Zona de perigo</p>

        {{-- Erros de exclusão --}}
        @if($errors->userDeletion->isNotEmpty())
        <div class="glass rounded-xl px-4 py-3 mb-4 border border-red-400/20">
            <p class="text-red-400/80 text-xs">{{ $errors->userDeletion->first('password') }}</p>
        </div>
        @endif

        {{-- Botão que abre o painel --}}
        <button id="delete-reveal-btn" type="button" onclick="floraRevealDelete()"
                class="w-full flex items-center justify-center gap-2 glass border border-red-900/20 text-red-400/60 hover:text-red-400 hover:border-red-400/30 text-xs uppercase tracking-widest py-3.5 rounded-full transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Solicitar exclusão de conta
        </button>

        {{-- Painel explicativo + confirmação --}}
        <div id="delete-confirm" style="display:none;" class="mt-5 space-y-4">

            {{-- O que acontece --}}
            <div class="glass rounded-2xl p-5 space-y-4">
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">O que acontece</p>

                <div class="space-y-3">
                    <div class="flex gap-3 items-start">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-[#C8A96E]/10 flex items-center justify-center text-[10px] text-[#C8A96E] font-bold mt-0.5">1</span>
                        <div>
                            <p class="text-[#EDE0CC] text-xs font-medium">Sua conta fica pausada por 30 dias</p>
                            <p class="text-[#7A8E72] text-[10px] mt-0.5">Você será desconectado imediatamente, mas seus dados ficam preservados.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-[#C8A96E]/10 flex items-center justify-center text-[10px] text-[#C8A96E] font-bold mt-0.5">2</span>
                        <div>
                            <p class="text-[#EDE0CC] text-xs font-medium">Você recebe um e-mail com link de reativação</p>
                            <p class="text-[#7A8E72] text-[10px] mt-0.5">Se mudar de ideia, basta clicar no link para recuperar tudo.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-red-400/20 flex items-center justify-center text-[10px] text-red-400 font-bold mt-0.5">3</span>
                        <div>
                            <p class="text-[#EDE0CC] text-xs font-medium">Após 30 dias, exclusão permanente</p>
                            <p class="text-[#7A8E72] text-[10px] mt-0.5">Diário Verde, cuidados, badges, XP e preferências serão apagados para sempre.</p>
                        </div>
                    </div>
                </div>

                {{-- O que será apagado --}}
                <div class="border-t border-white/[0.06] pt-4">
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Será excluído permanentemente</p>
                    <div class="grid grid-cols-2 gap-1.5">
                        @foreach(['Diário Verde','Histórico de cuidados','Alertas e notificações','Conquistas e XP','Dados de perfil','Preferências'] as $item)
                        <div class="flex items-center gap-1.5 text-[10px] text-[#7A8E72]">
                            <span class="text-red-400/50 text-[8px]">✕</span> {{ $item }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Formulário de confirmação --}}
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3">
                @csrf
                @method('DELETE')

                <div>
                    <label for="delete_password" class="block text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-2">Confirme sua senha para continuar</label>
                    <input id="delete_password" name="password" type="password"
                           placeholder="Sua senha atual"
                           autocomplete="current-password"
                           class="w-full glass border border-red-900/20 focus:border-red-400/40 text-[#EDE0CC] placeholder-[#3A5E2D]/50 text-sm rounded-xl px-4 py-3 focus:outline-none transition-all duration-200">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="floraRevealDelete()"
                            class="flex-1 glass border border-white/[0.07] text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest py-3 rounded-full transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 bg-red-500/10 border border-red-400/25 text-red-400 hover:bg-red-500/20 hover:border-red-400/40 text-xs uppercase tracking-widest py-3 rounded-full transition-all duration-200">
                        Solicitar exclusão
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function(e) {
        var preview = document.getElementById('avatar-preview');
        var initials = document.getElementById('avatar-initials');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (initials) initials.classList.add('hidden');
        document.getElementById('avatar-label').textContent = input.files[0].name;
    };
    reader.readAsDataURL(input.files[0]);
}

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
