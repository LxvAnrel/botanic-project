@extends('layouts.app')

@section('title', 'Quiz · Encontre sua Planta Perfeita')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="font-display text-4xl font-bold text-slate-900 mb-2">Encontre sua planta perfeita</h1>
            <p class="text-slate-500">4 perguntas rápidas e recomendamos a planta ideal para o seu estilo de vida.</p>
        </div>
        <livewire:plant-quiz />
    </div>
</div>
@endsection
