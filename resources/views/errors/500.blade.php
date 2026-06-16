@extends('layouts.app')

@section('title', 'Erro interno')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-5">
    <div class="text-center max-w-md">
        <p class="font-serif text-[clamp(5rem,15vw,10rem)] text-[#C8A96E]/10 leading-none select-none">500</p>
        <div class="-mt-6 mb-6">
            <span class="text-5xl">🌵</span>
        </div>
        <h1 class="font-serif font-light text-3xl text-[#EDE0CC] mb-3">Algo deu errado</h1>
        <p class="text-[#7A8E72] text-sm leading-relaxed mb-8">
            Encontramos um problema interno. Nossa equipe já foi notificada. Tente novamente em alguns instantes.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold px-7 py-3.5 rounded-full hover:bg-[#D4BA8A] transition-all duration-200">
                Voltar ao início
            </a>
            <button onclick="location.reload()" class="inline-flex items-center gap-2 glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest px-7 py-3.5 rounded-full transition-all duration-200">
                Tentar novamente
            </button>
        </div>
    </div>
</div>
@endsection
