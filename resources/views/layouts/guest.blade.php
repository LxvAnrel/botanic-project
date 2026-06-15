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
<body class="min-h-screen flex flex-col items-center justify-center py-12 px-4 relative overflow-hidden">

    {{-- Ambient orbs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-[#C8A96E]/10 blur-[110px]"></div>
        <div class="absolute -bottom-32 -left-32 w-[450px] h-[450px] rounded-full bg-[#2D6A2D]/14 blur-[100px]"></div>
    </div>

    {{-- Logo --}}
    <a href="/" class="mb-10 text-center relative z-10">
        <span class="block font-serif text-3xl tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
        <span class="block text-[9px] uppercase tracking-[0.4em] text-[#3A5E2D] mt-1">Botânica Interativa</span>
    </a>

    {{-- Glass card --}}
    <div class="w-full max-w-md relative z-10">
        <div class="glass rounded-3xl p-8">
            {{ $slot }}
        </div>
    </div>

    <p class="mt-8 text-[#2D4A23] text-[10px] uppercase tracking-widest relative z-10">
        © {{ date('Y') }} Flora · Plataforma Botânica
    </p>

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
