<x-guest-layout>
    <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-6">— Crie sua conta</p>

    @if($errors->any())
        <x-auth-error :messages="['Confira os campos destacados abaixo e tente novamente.']" title="Não foi possível criar a conta" />
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5" onsubmit="floraSubmit(this)">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Como podemos te chamar?"
                          class="@error('name') border-red-400/50 @enderror" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="voce@exemplo.com"
                          class="@error('email') border-red-400/50 @enderror" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Senha')" />
            <x-flora-password id="password" name="password" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <p class="text-[10px] text-[#3A5E2D] mt-1.5 tracking-wide">Use ao menos 8 caracteres.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
            <x-flora-password id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Repita a senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-[#22381B]">
            <a href="{{ route('login') }}" class="text-xs text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">
                Já tem conta?
            </a>
            <x-primary-button data-label="Registrar →" data-loading="Criando…">Registrar →</x-primary-button>
        </div>
    </form>
</x-guest-layout>
