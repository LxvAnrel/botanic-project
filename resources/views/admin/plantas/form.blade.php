@extends('admin.layout')
@section('title', $planta->id ? 'Editar · ' . $planta->nome_popular : 'Nova Planta')
@section('breadcrumb', 'Plantas')
@section('back_url', '/admin/plantas')
@section('back_label', 'Plantas')

@section('content')

@php $edit = (bool) $planta->id; @endphp

<div class="max-w-2xl">
<form method="POST"
      action="{{ $edit ? '/admin/plantas/' . $planta->id : '/admin/plantas' }}"
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @if($edit) @method('PUT') @endif

    @if($errors->any())
    <div class="px-4 py-3 bg-red-900/20 border border-red-900/40 rounded-xl text-sm text-red-400">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="glass rounded-2xl p-6 space-y-5">
        <p class="text-[9px] uppercase tracking-widest text-[#7A8E72]">Identificação</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Nome popular *</label>
                <input type="text" name="nome_popular" value="{{ old('nome_popular', $planta->nome_popular) }}" required
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Nome científico *</label>
                <input type="text" name="nome_cientifico" value="{{ old('nome_cientifico', $planta->nome_cientifico) }}" required
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Família</label>
                <input type="text" name="familia" value="{{ old('familia', $planta->familia) }}"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Origem</label>
                <input type="text" name="origem" value="{{ old('origem', $planta->origem) }}"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 space-y-5">
        <p class="text-[9px] uppercase tracking-widest text-[#7A8E72]">Cuidados</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Luminosidade *</label>
                <select name="habitat_luz" required
                        class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 bg-[#131F11]">
                    <option value="">Selecionar</option>
                    @foreach(['sol_pleno' => '☀ Sol pleno', 'meia_sombra' => '◑ Meia sombra', 'sombra' => '● Sombra'] as $v => $l)
                    <option value="{{ $v }}" {{ old('habitat_luz', $planta->habitat_luz) === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Porte máximo (cm)</label>
                <input type="number" name="porte_max_cm" value="{{ old('porte_max_cm', $planta->porte_max_cm) }}" min="1"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Intervalo de rega (dias)</label>
                <input type="number" name="dias_entre_regas" value="{{ old('dias_entre_regas', $planta->dias_entre_regas) }}" min="1"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Intervalo de adubação (dias)</label>
                <input type="number" name="dias_entre_adubacoes" value="{{ old('dias_entre_adubacoes', $planta->dias_entre_adubacoes) }}" min="1"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Época de poda</label>
                <input type="text" name="epoca_poda" value="{{ old('epoca_poda', $planta->epoca_poda) }}" placeholder="ex: primavera"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div class="flex items-center gap-3 pt-5">
                <input type="hidden" name="toxica_pets" value="0">
                <input type="checkbox" name="toxica_pets" value="1" id="toxica"
                       class="w-4 h-4 accent-[#C8A96E]"
                       {{ old('toxica_pets', $planta->toxica_pets) ? 'checked' : '' }}>
                <label for="toxica" class="text-sm text-[#EDE0CC] cursor-pointer">Tóxica para pets</label>
            </div>
        </div>

        <div>
            <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Curiosidades</label>
            <textarea name="curiosidades" rows="3"
                      class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 resize-none">{{ old('curiosidades', $planta->curiosidades) }}</textarea>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 space-y-4">
        <p class="text-[9px] uppercase tracking-widest text-[#7A8E72]">Imagem</p>
        @if($edit && $planta->image_path)
        <div class="flex items-center gap-4">
            <img src="{{ asset($planta->image_path) }}" class="w-20 h-20 object-cover rounded-xl opacity-80">
            <p class="text-xs text-[#7A8E72]">Envie uma nova imagem para substituir</p>
        </div>
        @endif
        <input type="file" name="image" accept="image/*"
               class="block text-sm text-[#7A8E72] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-medium file:bg-[#C8A96E]/10 file:text-[#C8A96E] hover:file:bg-[#C8A96E]/20 cursor-pointer">
        <p class="text-[10px] text-[#3A5E2D]">JPG, PNG ou WebP · máx. 4MB</p>
    </div>

    <div class="flex gap-3">
        <button type="submit"
                class="bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold px-7 py-3 rounded-xl hover:bg-[#D4BA8A] transition-all">
            {{ $edit ? 'Salvar alterações' : 'Criar planta' }}
        </button>
        <a href="/admin/plantas" class="glass border border-white/[0.08] text-[#7A8E72] text-xs uppercase tracking-widest px-7 py-3 rounded-xl hover:text-[#C8A96E] transition-all">
            Cancelar
        </a>
    </div>
</form>
</div>

@endsection
