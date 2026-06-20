<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha seu apelido · Flora Botanic</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .card-enter { animation: fadeIn .45s cubic-bezier(.22,.68,0,1.2) both; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinning { animation: spin .6s linear; }
    </style>
</head>
<body class="bg-[#0B160A]">

    {{-- Backdrop desfocado que bloqueia a página atrás --}}
    <div class="fixed inset-0 z-10 backdrop-blur-sm bg-black/60"></div>

    {{-- Modal --}}
    <div class="relative z-20 w-full max-w-md mx-4 card-enter">
        <div class="glass rounded-3xl p-8 shadow-2xl border border-white/10">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Flora Botanic" class="h-12 w-auto"
                     onerror="this.style.display='none'">
            </div>

            {{-- Título --}}
            <h1 class="text-2xl font-bold text-white text-center mb-1">Escolha seu apelido</h1>
            <p class="text-white/50 text-sm text-center mb-6">
                Seu apelido é único e será exibido para outros usuários.<br>
                Você pode usar letras, números e underscore ( _ ).
            </p>

            {{-- Erros --}}
            @if($errors->any())
                <div class="bg-red-500/15 border border-red-500/30 rounded-xl p-3 mb-5 text-sm text-red-300">
                    {{ $errors->first('nickname') }}
                </div>
            @endif

            {{-- Preview do nick gerado --}}
            <div id="preview-box" class="glass-deep rounded-xl px-4 py-3 mb-4 flex items-center justify-between gap-3">
                <span class="text-white/40 text-xs uppercase tracking-wider">Sugestão</span>
                <span id="preview-nick" class="text-[#9AA88E] font-mono font-bold text-lg tracking-wide">
                    {{ old('nickname', $sugestao ?? '') }}
                </span>
                <button type="button" id="btn-gerar"
                        title="Gerar outro"
                        class="text-white/40 hover:text-[#9AA88E] transition-colors">
                    <svg id="icon-gerar" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>

            {{-- Botão usar sugestão --}}
            <button type="button" id="btn-usar-sugestao"
                    class="w-full glass-gold rounded-xl py-2 text-sm font-semibold text-white/80 hover:text-white transition mb-5">
                Usar essa sugestão
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-white/10"></div>
                <span class="text-white/30 text-xs">ou escreva o seu</span>
                <div class="flex-1 h-px bg-white/10"></div>
            </div>

            {{-- Formulário --}}
            <form method="POST" action="{{ route('nickname.salvar') }}" id="form-nick">
                @csrf

                <div class="relative mb-4">
                    <input id="nick-input"
                           type="text"
                           name="nickname"
                           maxlength="20"
                           autocomplete="off"
                           autofocus
                           placeholder="MeuApelido"
                           value="{{ old('nickname') }}"
                           class="w-full glass-deep rounded-xl px-4 py-3 text-white placeholder-white/30
                                  border border-white/10 focus:border-[#9AA88E]/50 focus:outline-none
                                  font-mono text-lg tracking-wide transition"
                    >
                    <span id="counter" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-white/30">
                        0/20
                    </span>
                </div>

                {{-- Validação ao vivo --}}
                <p id="hint" class="text-xs text-white/40 mb-5 min-h-[1rem]"></p>

                <button type="submit" id="btn-confirmar"
                        class="w-full glass-gold rounded-xl py-3 font-bold text-white text-base
                               hover:brightness-110 active:scale-95 transition-all
                               disabled:opacity-40 disabled:cursor-not-allowed"
                        disabled>
                    Confirmar apelido
                </button>

                <p class="text-center text-white/25 text-xs mt-4">
                    Você não pode fechar esta tela sem escolher um apelido.
                </p>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const input   = document.getElementById('nick-input');
        const counter = document.getElementById('counter');
        const hint    = document.getElementById('hint');
        const btnOk   = document.getElementById('btn-confirmar');
        const btnGen  = document.getElementById('btn-gerar');
        const icon    = document.getElementById('icon-gerar');
        const preview = document.getElementById('preview-nick');
        const btnUsar = document.getElementById('btn-usar-sugestao');
        const form    = document.getElementById('form-nick');
        const RE      = /^[a-zA-Z0-9_]+$/;

        function validar(v) {
            if (!v) return '';
            if (v.length < 3) return 'Mínimo 3 caracteres.';
            if (!RE.test(v))  return 'Apenas letras, números e underscore ( _ ).';
            return '';
        }

        function atualizar() {
            const v = input.value.trim();
            counter.textContent = v.length + '/20';
            const erro = validar(v);
            hint.textContent = erro;
            hint.className   = erro ? 'text-xs text-red-400 mb-5 min-h-[1rem]'
                                    : 'text-xs text-[#9AA88E]/60 mb-5 min-h-[1rem]';
            btnOk.disabled = !!erro || v.length < 3;
        }

        input.addEventListener('input', atualizar);
        atualizar();

        // Gerar sugestão via AJAX
        btnGen.addEventListener('click', function () {
            icon.classList.add('spinning');
            fetch('{{ route('nickname.gerar') }}')
                .then(r => r.json())
                .then(d => { preview.textContent = d.nickname; })
                .finally(() => { setTimeout(() => icon.classList.remove('spinning'), 650); });
        });

        // Copiar sugestão para o input
        btnUsar.addEventListener('click', function () {
            input.value = preview.textContent.trim();
            atualizar();
            input.focus();
        });

        // Bloqueia fechar a aba / navegar — aviso nativo
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = '';
        });

        // Bloqueia o botão voltar navegando de volta para cá
        history.pushState(null, '', location.href);
        window.addEventListener('popstate', function () {
            history.pushState(null, '', location.href);
        });

        // Remove o beforeunload ao submeter normalmente
        form.addEventListener('submit', function () {
            window.removeEventListener('beforeunload', arguments.callee);
        });
    })();
    </script>
</body>
</html>
