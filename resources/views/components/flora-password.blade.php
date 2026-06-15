@props(['id', 'name', 'autocomplete' => 'current-password', 'placeholder' => ''])

@php $borderCls = $errors->has($name) ? 'border-red-400/50' : 'border-white/[0.08]'; @endphp

<div class="relative">
    <input id="{{ $id }}" name="{{ $name }}" type="password" required
           autocomplete="{{ $autocomplete }}" placeholder="{{ $placeholder }}"
           class="w-full glass text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm pl-4 pr-11 py-3 rounded-xl focus:outline-none focus:border-[#C8A96E]/50 transition-all duration-200 {{ $borderCls }}">
    <button type="button" tabindex="-1" aria-label="Mostrar senha"
            onclick="floraTogglePassword(this, '{{ $id }}')"
            class="absolute inset-y-0 right-0 px-3.5 flex items-center text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">
        <svg data-eye="open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <svg data-eye="closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
    </button>
</div>
