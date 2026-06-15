@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">

    <div class="mb-10 border-b border-white/[0.06] pb-8">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-3">— Conta</p>
        <h1 class="font-serif font-light text-5xl text-[#EDE0CC]">Meu Perfil</h1>
    </div>

    <div class="max-w-xl space-y-4">

        {{-- Informações --}}
        <div class="glass rounded-3xl p-8 space-y-6">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Informações Pessoais</p>
            <div class="space-y-5 border-t border-white/[0.06] pt-5">
                <div>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Nome</p>
                    <p class="text-[#EDE0CC] text-sm">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Email</p>
                    <p class="text-[#EDE0CC] text-sm">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Membro desde</p>
                    <p class="text-[#EDE0CC] text-sm">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Estatísticas --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="glass-gold rounded-3xl p-8 text-center">
                <p class="font-serif text-4xl text-[#C8A96E] mb-1" style="text-shadow:0 0 30px rgba(200,169,110,0.4)">{{ auth()->user()->plants()->count() }}</p>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72]">Plantas no Diário</p>
            </div>
            <div class="glass rounded-3xl p-8 text-center">
                <p class="font-serif text-4xl text-[#C8A96E] mb-1">{{ auth()->user()->notifications()->count() }}</p>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72]">Notificações</p>
            </div>
        </div>

        {{-- Ações --}}
        <div class="glass rounded-3xl p-8 space-y-3">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Ações</p>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center justify-center gap-2 glass-gold text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest py-3 rounded-full transition-all duration-200">
                Editar perfil
            </a>
        </div>
    </div>
</div>
@endsection
