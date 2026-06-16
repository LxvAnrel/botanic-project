<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
    @endauth
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
            <div class="glass rounded-2xl px-4 md:px-6">
                <div class="flex items-center justify-between h-14 relative">

                    {{-- Nav esq (desktop) --}}
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

                    {{-- Hamburguer (mobile) --}}
                    <button id="nav-toggle" onclick="floraNavToggle()" class="md:hidden flex flex-col gap-1.5 p-2 rounded-xl hover:bg-white/5 transition-colors" aria-label="Menu">
                        <span id="nav-bar1" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                        <span id="nav-bar2" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                        <span id="nav-bar3" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                    </button>

                    {{-- Logo centro --}}
                    <a href="/" class="absolute left-1/2 -translate-x-1/2 text-center">
                        <span class="font-serif text-2xl tracking-[0.2em] text-[#C8A96E] uppercase leading-none">Flora</span>
                        <span class="block text-[8px] uppercase tracking-[0.4em] text-[#7A8E72] mt-0.5">Botânica Interativa</span>
                    </a>

                    {{-- Nav dir (desktop) --}}
                    <nav class="hidden md:flex items-center gap-1">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Diário
                            </a>
                            <a href="{{ route('alertas') }}"
                               class="relative text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Alertas
                                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                                @if($unread > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 text-[8px] bg-[#C8A96E] text-[#0E1A0B] rounded-full flex items-center justify-center font-bold">{{ $unread > 9 ? '9+' : $unread }}</span>
                                @endif
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

            {{-- Mobile menu --}}
            <div id="mobile-menu"
                 class="md:hidden glass rounded-2xl mt-2 p-4 space-y-1 overflow-hidden transition-all duration-200 ease-out"
                 style="display:none;">
                <a href="{{ route('plants.index') }}"
                   class="block text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                    Catálogo
                </a>
                <a href="{{ route('quiz') }}"
                   class="block text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                    Quiz
                </a>
                <div class="border-t border-white/[0.06] my-2"></div>
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="block text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                        Meu Diário
                    </a>
                    <a href="{{ route('alertas') }}"
                       class="flex items-center justify-between text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                        Alertas
                        @php $unreadMobile = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unreadMobile > 0)
                        <span class="w-5 h-5 text-[9px] bg-[#C8A96E] text-[#0E1A0B] rounded-full flex items-center justify-center font-bold">{{ $unreadMobile > 9 ? '9+' : $unreadMobile }}</span>
                        @endif
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-red-400 hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                            Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}"
                       class="block glass-gold text-[#C8A96E] text-sm uppercase tracking-widest font-medium px-4 py-3 rounded-xl transition-all duration-200 text-center mt-1">
                        Registrar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <script>
        function floraNavToggle() {
            var menu = document.getElementById('mobile-menu');
            var b1 = document.getElementById('nav-bar1');
            var b2 = document.getElementById('nav-bar2');
            var b3 = document.getElementById('nav-bar3');
            var open = menu.style.display === 'block';
            menu.style.display = open ? 'none' : 'block';
            b1.style.transform = open ? '' : 'rotate(45deg) translateY(8px)';
            b2.style.opacity  = open ? '' : '0';
            b3.style.transform = open ? '' : 'rotate(-45deg) translateY(-8px)';
        }
    </script>

    {{-- Flash: conta agendada para exclusão --}}
    @if(session('conta_agendada_exclusao'))
    <div id="flora-flash-exclusao"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] w-[min(92vw,480px)] glass rounded-2xl p-5 border border-amber-400/20 shadow-[0_8px_40px_rgba(0,0,0,0.4)]">
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-10 h-10 bg-amber-400/10 rounded-full flex items-center justify-center text-xl">⏳</div>
            <div class="flex-1">
                <p class="text-[#EDE0CC] text-sm font-medium">Solicitação recebida</p>
                <p class="text-[#7A8E72] text-xs leading-relaxed mt-1">
                    Sua conta está agendada para exclusão. Enviamos um e-mail com um link para cancelar caso mude de ideia. Você tem <strong class="text-[#C8A96E]">30 dias</strong> para reativar.
                </p>
            </div>
            <button onclick="document.getElementById('flora-flash-exclusao').remove()"
                    class="shrink-0 text-[#7A8E72] hover:text-[#C8A96E] text-lg leading-none mt-0.5 transition-colors">×</button>
        </div>
    </div>
    @endif

    {{-- Flash: conta reativada --}}
    @if(session('status') && str_contains(session('status'), 'reativada'))
    <div id="flora-flash-reativada"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[200] w-[min(92vw,480px)] glass-gold rounded-2xl p-5 border border-[#C8A96E]/30 shadow-[0_8px_40px_rgba(200,169,110,0.2)]">
        <div class="flex items-start gap-4">
            <div class="shrink-0 w-10 h-10 bg-[#C8A96E]/15 rounded-full flex items-center justify-center text-xl">🌱</div>
            <div class="flex-1">
                <p class="text-[#EDE0CC] text-sm font-medium">Conta reativada!</p>
                <p class="text-[#7A8E72] text-xs leading-relaxed mt-1">{{ session('status') }}</p>
            </div>
            <button onclick="document.getElementById('flora-flash-reativada').remove()"
                    class="shrink-0 text-[#7A8E72] hover:text-[#C8A96E] text-lg leading-none mt-0.5 transition-colors">×</button>
        </div>
    </div>
    @endif

    <main class="flex-1 relative z-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 mt-24 px-4 pb-4">
        <div class="max-w-7xl mx-auto glass rounded-3xl px-5 md:px-8 lg:px-12 py-8 md:py-14">
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
    @auth
        <script src="{{ asset('js/push.js') }}"></script>
    @endauth
</body>
</html>
