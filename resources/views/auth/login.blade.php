<x-guest-layout>
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-6">— Acesse sua conta</p>

    <x-auth-error :messages="$errors->all()" title="Não foi possível entrar" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" onsubmit="floraSubmit(this)">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="voce@exemplo.com"
                          class="@error('email') border-red-400/50 @enderror" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Senha')" />
            <x-flora-password id="password" name="password" autocomplete="current-password" placeholder="Sua senha" />
        </div>

        <div class="flex items-center gap-3 pt-1">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-3.5 h-3.5 border border-[#2D4A23] bg-[#1A2B15] accent-[#C8A96E]">
            <label for="remember_me" class="text-xs text-[#7A8E72] cursor-pointer">Lembrar de mim</label>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-[#22381B]">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">
                    Esqueceu a senha?
                </a>
            @endif
            <x-primary-button data-label="Entrar →" data-loading="Entrando…">Entrar →</x-primary-button>
        </div>
    </form>

    <p class="text-center text-xs text-[#3A5E2D] mt-6">
        Não tem conta?
        <a href="{{ route('register') }}" class="text-[#C8A96E] hover:text-[#D4BA8A] transition-colors">Registrar</a>
    </p>
</x-guest-layout>
