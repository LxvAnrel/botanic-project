<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Flora') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Compensa o zoom do body (app.css) para que o body não ultrapasse o viewport */
        body { min-height: 100vh; }
        @media (min-width: 1536px) { body { min-height: calc(100vh / 1.15); } }
        @media (min-width: 1728px) { body { min-height: calc(100vh / 1.28); } }
        @media (min-width: 1920px) { body { min-height: calc(100vh / 1.4);  } }
    </style>
</head>
<body style="background:#0B160A; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2rem 1rem; box-sizing:border-box;">

    {{-- Ambient orbs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-[#C8A96E]/10 blur-[110px]"></div>
        <div class="absolute -bottom-32 -left-32 w-[450px] h-[450px] rounded-full bg-[#2D6A2D]/14 blur-[100px]"></div>
    </div>

    {{-- grid + place-items:center centraliza vertical e horizontalmente sem depender de height fixo --}}
    <div style="width:100%; max-width:30rem; display:flex; flex-direction:column; align-items:center; gap:2rem; position:relative; z-index:10;">

        {{-- Logo --}}
        <a href="/" style="text-align:center; text-decoration:none;">
            <span style="display:block; font-family:'Cormorant Garamond',serif; font-size:1.875rem; letter-spacing:.2em; color:#C8A96E; text-transform:uppercase;">Flora</span>
            <span style="display:block; font-size:9px; text-transform:uppercase; letter-spacing:.4em; color:#3A5E2D; margin-top:4px;">Botânica Interativa</span>
        </a>

        {{-- Glass card --}}
        <div style="width:100%;">
            <div class="glass rounded-3xl p-8">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer --}}
        <p style="color:#2D4A23; font-size:10px; text-transform:uppercase; letter-spacing:.15em; text-align:center;">
            © {{ date('Y') }} Flora · Plataforma Botânica
        </p>

    </div>

    <script>
        function floraTogglePassword(btn, inputId) {
            var input = document.getElementById(inputId);
            var open = btn.querySelector('[data-eye="open"]');
            var closed = btn.querySelector('[data-eye="closed"]');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            open.classList.toggle('hidden', show);
            closed.classList.toggle('hidden', !show);
            btn.setAttribute('aria-label', show ? 'Ocultar senha' : 'Mostrar senha');
        }

        function floraSubmit(form) {
            var btn = form.querySelector('button[type="submit"], [data-loading]');
            if (btn && btn.dataset.loading) {
                btn.disabled = true;
                btn.textContent = btn.dataset.loading;
            }
            return true;
        }
    </script>
</body>
</html>
