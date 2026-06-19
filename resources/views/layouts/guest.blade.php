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
</head>
<body class="relative overflow-x-hidden" style="background:#0B160A;">

    {{-- Ambient orbs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-[#C8A96E]/10 blur-[110px]"></div>
        <div class="absolute -bottom-32 -left-32 w-[450px] h-[450px] rounded-full bg-[#2D6A2D]/14 blur-[100px]"></div>
    </div>

    {{-- Wrapper centralizado: sempre pelo menos 100vh, rola se precisar --}}
    <div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:2.5rem 1rem; gap:2rem; position:relative; z-index:10;">

        {{-- Logo --}}
        <a href="/" class="text-center">
            <span class="block font-serif text-3xl tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
            <span class="block text-[9px] uppercase tracking-[0.4em] text-[#3A5E2D] mt-1">Botânica Interativa</span>
        </a>

        {{-- Glass card --}}
        <div class="w-full max-w-md">
            <div class="glass rounded-3xl p-8">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer dentro do wrapper --}}
        <p class="text-[#2D4A23] text-[10px] uppercase tracking-widest">
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
