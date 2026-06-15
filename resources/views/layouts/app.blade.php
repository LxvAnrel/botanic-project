<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Botanica') · Flora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col relative">

    {{-- Ambient colour orbs (give glass something to blur against) --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-[#C8A96E]/8 blur-[120px]"></div>
        <div class="absolute top-1/3 -left-60 w-[500px] h-[500px] rounded-full bg-[#2D6A2D]/12 blur-[100px]"></div>
        <div class="absolute -bottom-20 right-1/4 w-[400px] h-[400px] rounded-full bg-[#1A4A1A]/20 blur-[90px]"></div>
    </div>

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 px-4 pt-3 pb-2">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-2xl px-6">
                <div class="flex items-center justify-between h-14">

                    {{-- Nav esq --}}
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('plants.index') }}"
                           class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                            Catálogo
                        </a>
                        <a href="{{ route('quiz') }}"
                           class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                            Quiz
                        </a>
                    </nav>

                    {{-- Logo centro --}}
                    <a href="/" class="absolute left-1/2 -translate-x-1/2 text-center">
                        <span class="font-serif text-2xl tracking-[0.2em] text-[#C8A96E] uppercase leading-none">Flora</span>
                        <span class="block text-[8px] uppercase tracking-[0.4em] text-[#7A8E72] mt-0.5">Botânica Interativa</span>
                    </a>

                    {{-- Nav dir --}}
                    <nav class="hidden md:flex items-center gap-1">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Diário
                            </a>
                            <a href="{{ route('alertas') }}"
                               class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Alertas
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                    Sair
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Entrar
                            </a>
                            <a href="{{ route('register') }}"
                               class="glass-gold text-[#C8A96E] hover:bg-[#C8A96E]/15 text-xs uppercase tracking-widest font-medium px-5 py-2 rounded-full transition-all duration-200">
                                Registrar
                            </a>
                        @endauth
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 relative z-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 mt-24 px-4 pb-4">
        <div class="max-w-7xl mx-auto glass rounded-3xl px-8 lg:px-12 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <p class="font-serif text-3xl text-[#C8A96E] tracking-widest uppercase mb-4">Flora</p>
                    <p class="text-[#7A8E72] text-sm leading-relaxed max-w-xs">
                        Seu guia botânico interativo. Descubra, cultive e cuide das plantas que transformam espaços.
                    </p>
                </div>
                <div>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mb-5">Explorar</p>
                    <ul class="space-y-3">
                        <li><a href="{{ route('plants.index') }}" class="link-gold text-sm">Catálogo de plantas</a></li>
                        <li><a href="{{ route('quiz') }}" class="link-gold text-sm">Quiz de recomendação</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-[#7A8E72] mb-5">Conta</p>
                    <ul class="space-y-3">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="link-gold text-sm">Meu Diário Verde</a></li>
                            <li><a href="{{ route('alertas') }}" class="link-gold text-sm">Alertas de poda</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="link-gold text-sm">Entrar</a></li>
                            <li><a href="{{ route('register') }}" class="link-gold text-sm">Criar conta</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/[0.06] pt-6 flex items-center justify-between">
                <p class="text-[#7A8E72] text-xs tracking-wide">© {{ date('Y') }} Flora · Plataforma Botânica</p>
                <p class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D]">Organic · Botanical</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
