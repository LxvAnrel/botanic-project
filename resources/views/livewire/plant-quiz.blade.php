<div class="max-w-xl mx-auto">
    @if(!$result)
    <div class="glass rounded-3xl overflow-hidden">

        {{-- Progress --}}
        <div class="border-b border-white/[0.06] px-8 py-5 flex items-center justify-between">
            <div class="flex gap-2">
                @for($i = 1; $i <= 4; $i++)
                    <div class="h-1 w-10 rounded-full transition-all duration-500 {{ $i <= $step ? 'bg-[#C8A96E] shadow-[0_0_8px_rgba(200,169,110,0.6)]' : 'bg-white/[0.08]' }}"></div>
                @endfor
            </div>
            <span class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D]">{{ $step }} / 4</span>
        </div>

        <div class="p-8 space-y-8">

            @if($step === 1)
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">— Passo 1</p>
                <h2 class="font-serif font-light text-3xl text-[#EDE0CC] mb-6">Você tem pets em casa?</h2>
                <div class="space-y-3">
                    <label wire:click="$set('answers.hasPets', false)"
                           class="flex items-center gap-5 p-5 rounded-2xl cursor-pointer transition-all duration-200 border
                               {{ isset($answers['hasPets']) && $answers['hasPets'] == false ? 'option-selected' : 'glass border-white/[0.06] hover:border-white/[0.14]' }}">
                        <span class="text-2xl">😊</span>
                        <div>
                            <p class="text-[#EDE0CC] text-sm font-medium">Não tenho pets</p>
                            <p class="text-[#7A8E72] text-xs mt-0.5">Liberdade para qualquer espécie</p>
                        </div>
                    </label>
                    <label wire:click="$set('answers.hasPets', true)"
                           class="flex items-center gap-5 p-5 rounded-2xl cursor-pointer transition-all duration-200 border
                               {{ isset($answers['hasPets']) && $answers['hasPets'] == true ? 'option-selected' : 'glass border-white/[0.06] hover:border-white/[0.14]' }}">
                        <span class="text-2xl">🐾</span>
                        <div>
                            <p class="text-[#EDE0CC] text-sm font-medium">Tenho pets</p>
                            <p class="text-[#7A8E72] text-xs mt-0.5">Vamos priorizar plantas não-tóxicas</p>
                        </div>
                    </label>
                </div>
            </div>
            @endif

            @if($step === 2)
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">— Passo 2</p>
                <h2 class="font-serif font-light text-3xl text-[#EDE0CC] mb-6">Quanto de luz tem seu ambiente?</h2>
                <div class="space-y-3">
                    @php $opts = [
                        ['value'=>'sol_pleno','icon'=>'☀','label'=>'Sol Pleno','desc'=>'Mais de 6h de sol direto por dia'],
                        ['value'=>'meia_sombra','icon'=>'◑','label'=>'Meia Sombra','desc'=>'Entre 3 e 6 horas de luz'],
                        ['value'=>'sombra','icon'=>'●','label'=>'Sombra','desc'=>'Menos de 3 horas de sol'],
                    ]; @endphp
                    @foreach($opts as $o)
                    <label wire:click="$set('answers.light', '{{ $o['value'] }}')"
                           class="flex items-center gap-5 p-5 rounded-2xl cursor-pointer transition-all duration-200 border
                               {{ ($answers['light'] ?? '') === $o['value'] ? 'option-selected' : 'glass border-white/[0.06] hover:border-white/[0.14]' }}">
                        <span class="text-[#C8A96E] text-xl w-6 text-center">{{ $o['icon'] }}</span>
                        <div>
                            <p class="text-[#EDE0CC] text-sm font-medium">{{ $o['label'] }}</p>
                            <p class="text-[#7A8E72] text-xs mt-0.5">{{ $o['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            @if($step === 3)
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">— Passo 3</p>
                <h2 class="font-serif font-light text-3xl text-[#EDE0CC] mb-6">Qual o tamanho do seu espaço?</h2>
                <div class="space-y-3">
                    @php $opts = [
                        ['value'=>'small','icon'=>'◻','label'=>'Pequeno','desc'=>'Até 50cm — mesas, prateleiras'],
                        ['value'=>'medium','icon'=>'◼','label'=>'Médio','desc'=>'50 a 150cm — varandas e cantinhos'],
                        ['value'=>'large','icon'=>'▣','label'=>'Grande','desc'=>'Mais de 150cm — jardins e espaços abertos'],
                    ]; @endphp
                    @foreach($opts as $o)
                    <label wire:click="$set('answers.space', '{{ $o['value'] }}')"
                           class="flex items-center gap-5 p-5 rounded-2xl cursor-pointer transition-all duration-200 border
                               {{ ($answers['space'] ?? '') === $o['value'] ? 'option-selected' : 'glass border-white/[0.06] hover:border-white/[0.14]' }}">
                        <span class="text-[#C8A96E] text-xl w-6 text-center">{{ $o['icon'] }}</span>
                        <div>
                            <p class="text-[#EDE0CC] text-sm font-medium">{{ $o['label'] }}</p>
                            <p class="text-[#7A8E72] text-xs mt-0.5">{{ $o['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            @if($step === 4)
            <div>
                <p class="text-[9px] uppercase tracking-[0.4em] text-[#7A8E72] mb-3">— Passo 4</p>
                <h2 class="font-serif font-light text-3xl text-[#EDE0CC] mb-6">Qual é a sua experiência?</h2>
                <div class="space-y-3">
                    @php $opts = [
                        ['value'=>'beginner','icon'=>'◌','label'=>'Iniciante','desc'=>'Ainda não tive plantas'],
                        ['value'=>'intermediate','icon'=>'◍','label'=>'Intermediário','desc'=>'Tenho algumas plantas e sei cuidar'],
                        ['value'=>'advanced','icon'=>'◉','label'=>'Avançado','desc'=>'Sou apaixonado por plantas'],
                    ]; @endphp
                    @foreach($opts as $o)
                    <label wire:click="$set('answers.experience', '{{ $o['value'] }}')"
                           class="flex items-center gap-5 p-5 rounded-2xl cursor-pointer transition-all duration-200 border
                               {{ ($answers['experience'] ?? '') === $o['value'] ? 'option-selected' : 'glass border-white/[0.06] hover:border-white/[0.14]' }}">
                        <span class="text-[#C8A96E] text-xl w-6 text-center">{{ $o['icon'] }}</span>
                        <div>
                            <p class="text-[#EDE0CC] text-sm font-medium">{{ $o['label'] }}</p>
                            <p class="text-[#7A8E72] text-xs mt-0.5">{{ $o['desc'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Erro de validação --}}
            @if($error)
            <p class="text-xs text-red-400/80 text-center -mt-2">{{ $error }}</p>
            @endif

            {{-- Navegação --}}
            <div class="flex justify-between pt-4 border-t border-white/[0.06]">
                <button wire:click="previousStep"
                        @if($step === 1) disabled @endif
                        class="text-xs uppercase tracking-widest text-[#3A5E2D] hover:text-[#7A8E72] transition-colors disabled:opacity-20 disabled:cursor-not-allowed px-4 py-2 rounded-full">
                    ← Anterior
                </button>
                <button wire:click="nextStep"
                        class="text-xs uppercase tracking-widest bg-[#C8A96E] text-[#0E1A0B] font-semibold px-8 py-3 rounded-full hover:bg-[#D4BA8A] transition-all duration-200 shadow-[0_0_20px_rgba(200,169,110,0.3)]">
                    @if($step === 4) Descobrir → @else Próximo → @endif
                </button>
            </div>
        </div>
    </div>

    @else
    {{-- Resultado --}}
    <div class="space-y-4">
        <div class="glass-gold rounded-3xl p-8 text-center">
            <p class="text-[9px] uppercase tracking-[0.5em] text-[#C8A96E] mb-4">— Combinação encontrada</p>
            <h2 class="font-serif font-light text-5xl text-[#EDE0CC] mb-1">{{ $result->nome_popular }}</h2>
            <p class="font-serif italic text-[#7A8E72]">{{ $result->nome_cientifico }}</p>
        </div>

        <div class="glass rounded-3xl overflow-hidden">
            @if($result->image_path)
                <img src="{{ asset($result->image_path) }}"
                     alt="{{ $result->nome_popular }}"
                     class="w-full h-64 object-cover opacity-80">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-[#1A3A1A] to-[#0D2010] flex items-center justify-center text-7xl opacity-30">🌿</div>
            @endif

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="glass rounded-2xl p-4">
                        <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Luz</p>
                        <p class="text-[#EDE0CC] text-sm">
                            @switch($result->habitat_luz)
                                @case('sol_pleno') ☀ Sol Pleno @break
                                @case('meia_sombra') ◑ Meia Sombra @break
                                @case('sombra') ● Sombra @break
                            @endswitch
                        </p>
                    </div>
                    <div class="glass rounded-2xl p-4">
                        <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] mb-1">Pets</p>
                        <p class="text-sm {{ $result->toxica_pets ? 'text-red-400/70' : 'text-[#C8A96E]' }}">
                            {{ $result->toxica_pets ? '⚠ Tóxica' : '🐾 Pet-friendly' }}
                        </p>
                    </div>
                </div>

                <p class="text-[#7A8E72] text-sm leading-relaxed">{{ $result->curiosidades }}</p>

                <div class="space-y-3">
                    <a href="{{ route('plants.show', $result) }}"
                       class="w-full block text-center bg-[#C8A96E] hover:bg-[#D4BA8A] text-[#0E1A0B] text-xs uppercase tracking-widest font-semibold py-4 rounded-full transition-all duration-200 shadow-[0_0_24px_rgba(200,169,110,0.3)]">
                        Ver detalhes completos →
                    </a>
                    @auth
                    <button onclick="addToFavorites({{ $result->id }})"
                            class="w-full glass-gold text-[#C8A96E] hover:text-[#D4BA8A] text-xs uppercase tracking-widest font-medium py-4 rounded-full transition-all duration-200">
                        Adicionar ao Diário Verde
                    </button>
                    @endauth
                </div>
            </div>
        </div>

        <button wire:click="resetQuiz"
                class="w-full glass text-[#3A5E2D] hover:text-[#7A8E72] text-xs uppercase tracking-widest py-4 rounded-full transition-all duration-200">
            Refazer o quiz
        </button>
    </div>
    @endif
</div>

<script>
function addToFavorites(plantId) {
    fetch(`/planta/${plantId}/favorite`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(r => r.json()).then(data => {
        alert(data.added ? '✓ Adicionada ao Diário Verde' : '✕ Removida do Diário Verde');
    });
}
</script>
