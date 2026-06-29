@extends('admin.layout')
@section('title', $planta->id ? 'Editar · ' . $planta->nome_popular : 'Nova Planta')
@section('breadcrumb', 'Plantas')
@section('back_url', '/admin/plantas')
@section('back_label', 'Plantas')

@section('content')
@php $edit = (bool) $planta->id; @endphp

<form method="POST"
      action="{{ $edit ? '/admin/plantas/' . $planta->id : '/admin/plantas' }}"
      enctype="multipart/form-data">
    @csrf
    @if($edit) @method('PUT') @endif

    @if($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-900/20 border border-red-900/40 rounded-xl text-sm text-red-400">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Topo do formulario: imagem e identificacao da planta --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        {{-- Imagem --}}
        <div>
            <div class="relative w-full aspect-square glass rounded-3xl overflow-hidden flex items-center justify-center">
                @if($edit && $planta->image_path)
                    <img id="plant-img-preview" src="{{ asset($planta->image_path) }}"
                         alt="{{ $planta->nome_popular }}" class="w-full h-full object-cover">
                @else
                    <div id="plant-img-placeholder" class="flex flex-col items-center gap-4 text-[#3A5E2D] p-8 text-center pointer-events-none">
                        <svg class="w-20 h-20 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-[10px] uppercase tracking-widest opacity-40">Nenhuma imagem</p>
                    </div>
                    <img id="plant-img-preview" class="w-full h-full object-cover hidden">
                @endif
            </div>
            <label for="plant-image"
                   class="mt-3 flex items-center justify-center gap-2 glass border border-dashed border-white/20
                          hover:border-[#C8A96E]/40 text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase
                          tracking-widest px-4 py-3 rounded-2xl cursor-pointer transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                {{ $edit && $planta->image_path ? 'Trocar imagem' : 'Enviar imagem' }}
                <span class="text-[#3A5E2D] normal-case tracking-normal ml-1">(JPG / PNG / WebP · máx. 4 MB)</span>
            </label>
            <input type="file" id="plant-image" name="image" accept="image/*" class="sr-only"
                   onchange="previewPlantImage(this)">
            @error('image')<p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>@enderror
        </div>

        {{-- Campos de identificacao da planta --}}
        <div class="flex flex-col justify-center space-y-6">

            <div>
                <label class="block text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">Família</label>
                <input type="text" name="familia" value="{{ old('familia', $planta->familia) }}"
                       placeholder="ex: Araceae"
                       class="w-full bg-transparent border-0 border-b border-white/[0.10] focus:border-[#C8A96E]/40
                              text-[#7A8E72] text-sm pb-2 focus:outline-none transition-colors placeholder-[#3A5E2D]/30">
            </div>

            <div>
                <label class="block text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">Nome popular *</label>
                <input type="text" name="nome_popular" value="{{ old('nome_popular', $planta->nome_popular) }}"
                       required placeholder="ex: Costela de Adão"
                       class="w-full bg-transparent border-0 border-b border-white/[0.10] focus:border-[#C8A96E]/40
                              font-serif font-light text-3xl text-[#EDE0CC] pb-2 focus:outline-none transition-colors
                              placeholder-[#3A5E2D]/30">
                @error('nome_popular')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">Nome científico *</label>
                <input type="text" name="nome_cientifico" value="{{ old('nome_cientifico', $planta->nome_cientifico) }}"
                       required placeholder="ex: Monstera deliciosa"
                       class="w-full bg-transparent border-0 border-b border-white/[0.10] focus:border-[#C8A96E]/40
                              font-serif italic text-[#7A8E72] text-xl pb-2 focus:outline-none transition-colors
                              placeholder-[#3A5E2D]/30">
                @error('nome_cientifico')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-2">Origem</label>
                <input type="text" name="origem" value="{{ old('origem', $planta->origem) }}"
                       placeholder="ex: América Tropical"
                       class="w-full bg-transparent border-0 border-b border-white/[0.10] focus:border-[#C8A96E]/40
                              text-[#EDE0CC] text-sm pb-2 focus:outline-none transition-colors placeholder-[#3A5E2D]/30">
            </div>

            {{-- Badges inline (espelho dos badges do catálogo) --}}
            <div class="flex flex-wrap gap-3 pt-1">
                <select name="habitat_luz" required
                        class="glass border-white/[0.08] text-[#EDE0CC] text-[9px] uppercase tracking-widest
                               px-4 py-2 rounded-full bg-[#0E1A0B] focus:outline-none focus:border-[#C8A96E]/40 cursor-pointer">
                    <option value="">— luminosidade —</option>
                    @foreach(['sol_pleno' => '☀ Sol Pleno', 'meia_sombra' => '◑ Meia Sombra', 'sombra' => '● Sombra'] as $v => $l)
                    <option value="{{ $v }}" {{ old('habitat_luz', $planta->habitat_luz) === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2 glass px-4 py-2 rounded-full">
                    <input type="number" name="porte_max_cm" value="{{ old('porte_max_cm', $planta->porte_max_cm) }}"
                           min="1" placeholder="—"
                           class="w-14 bg-transparent text-[#EDE0CC] text-[9px] focus:outline-none text-center">
                    <span class="text-[9px] uppercase tracking-widest text-[#7A8E72]">cm máx</span>
                </div>

                <label class="flex items-center gap-2 glass px-4 py-2 rounded-full cursor-pointer hover:border-[#C8A96E]/30 transition-all">
                    <input type="hidden" name="toxica_pets" value="0">
                    <input type="checkbox" name="toxica_pets" value="1"
                           class="w-3.5 h-3.5 accent-[#C8A96E]"
                           {{ old('toxica_pets', $planta->toxica_pets) ? 'checked' : '' }}>
                    <span class="text-[9px] uppercase tracking-widest text-[#EDE0CC]">⚠ Tóxica pets</span>
                </label>
            </div>

            @error('habitat_luz')<p class="text-xs text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Cards de conteudo: cultivo, cuidados e curiosidades --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">

        <div class="glass rounded-3xl p-8 space-y-5">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E]">Cuidados</p>
            <div>
                <label class="block text-[9px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Regas (a cada N dias)</label>
                <input type="number" name="dias_entre_regas"
                       value="{{ old('dias_entre_regas', $planta->dias_entre_regas) }}" min="1"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[9px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Adubações (a cada N dias)</label>
                <input type="number" name="dias_entre_adubacoes"
                       value="{{ old('dias_entre_adubacoes', $planta->dias_entre_adubacoes) }}" min="1"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[9px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Época de poda</label>
                <input type="text" name="epoca_poda"
                       value="{{ old('epoca_poda', is_array($planta->epoca_poda) ? implode(', ', $planta->epoca_poda) : '') }}"
                       placeholder="primavera, verão"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
                <p class="text-[9px] text-[#3A5E2D] mt-1.5">Separar por vírgula</p>
            </div>
        </div>

        <div class="glass rounded-3xl p-8 flex flex-col">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Benefícios</p>
            <textarea name="beneficios" rows="7"
                      placeholder="Purificação do ar, propriedades medicinais, bem-estar..."
                      class="flex-1 bg-transparent text-[#7A8E72] text-sm leading-relaxed resize-none focus:outline-none placeholder-[#3A5E2D]/40">{{ old('beneficios', $planta->beneficios) }}</textarea>
        </div>

        <div class="glass rounded-3xl p-8 flex flex-col">
            <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Cuidados / Alertas</p>
            <textarea name="maleficios" rows="7"
                      placeholder="Riscos, toxicidade, cuidados especiais, contraindicações..."
                      class="flex-1 bg-transparent text-[#7A8E72] text-sm leading-relaxed resize-none focus:outline-none placeholder-[#3A5E2D]/40">{{ old('maleficios', $planta->maleficios) }}</textarea>
        </div>
    </div>

    <div class="glass rounded-3xl p-8 mb-8">
        <p class="text-[9px] uppercase tracking-[0.3em] text-[#C8A96E] mb-4">Curiosidades</p>
        <textarea name="curiosidades" rows="4"
                  placeholder="Fatos curiosos, história, lendas, usos culturais..."
                  class="w-full bg-transparent text-[#7A8E72] text-sm leading-relaxed resize-none focus:outline-none placeholder-[#3A5E2D]/40">{{ old('curiosidades', $planta->curiosidades) }}</textarea>
    </div>

    {{-- Ações --}}
    <div class="flex gap-3">
        <button type="submit"
                class="bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold
                       px-8 py-3.5 rounded-full hover:bg-[#D4BA8A] transition-all">
            {{ $edit ? 'Salvar alterações' : 'Criar planta' }}
        </button>
        <a href="/admin/plantas"
           class="glass border border-white/[0.08] text-[#7A8E72] text-xs uppercase tracking-widest
                  px-8 py-3.5 rounded-full hover:text-[#C8A96E] transition-all">
            Cancelar
        </a>
    </div>
</form>

<script>
function previewPlantImage(input) {
    if (!input.files || !input.files[0]) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = document.getElementById('plant-img-preview');
        var ph  = document.getElementById('plant-img-placeholder');
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (ph) ph.classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>

@endsection
