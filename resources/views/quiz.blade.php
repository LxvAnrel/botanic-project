@extends('layouts.app')

@section('title', 'Quiz · Encontre sua Planta Perfeita')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">
    <div class="max-w-xl mx-auto">
        <div class="mb-10 text-center">
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Recomendação Personalizada</p>
            <h1 class="font-serif font-light text-5xl text-[#EDE0CC] mb-2">Encontre sua planta<br><em class="text-[#C8A96E]">perfeita</em></h1>
            <p class="text-[#7A8E72] text-sm mt-4">4 perguntas rápidas e recomendamos a espécie ideal para você.</p>
        </div>
        <livewire:plant-quiz />
    </div>
</div>
@endsection
