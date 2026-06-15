@extends('layouts.app')

@section('title', 'Catálogo de Plantas')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">
    <div class="mb-10 border-b border-white/[0.06] pb-8">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-3">— Coleção Botânica</p>
        <h1 class="font-serif font-light text-5xl text-[#EDE0CC] mb-2">Catálogo de Plantas</h1>
        <p class="text-[#7A8E72] text-sm">Explore nossa coleção e encontre a espécie ideal para o seu espaço.</p>
    </div>
    <livewire:plant-catalog />
</div>
@endsection
