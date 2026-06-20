@extends('layouts.app')

@section('title', 'Acesso negado')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-5">
    <div class="text-center max-w-md">
        <p class="font-serif text-[clamp(5rem,15vw,10rem)] text-[#C8A96E]/10 leading-none select-none">403</p>
        <div class="-mt-6 mb-6">
            <span class="text-5xl">🔒</span>
        </div>
        <h1 class="font-serif font-light text-3xl text-[#EDE0CC] mb-3">Acesso negado</h1>
        <p class="text-[#7A8E72] text-sm leading-relaxed mb-8">
            Você não tem permissão para acessar esta página.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold px-7 py-3.5 rounded-full hover:bg-[#D4BA8A] transition-all duration-200">
                Voltar ao início
            </a>
            <a href="{{ route('plants.index') }}" class="inline-flex items-center gap-2 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest px-7 py-3.5 rounded-full transition-all duration-200">
                Ver catálogo
            </a>
        </div>
    </div>
</div>
@endsection
