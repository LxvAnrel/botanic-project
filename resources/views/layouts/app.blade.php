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

                    {{-- Esq: hamburguer de navegação (mobile) + links (desktop) --}}
                    <div class="flex items-center gap-1">
                        {{-- Hamburguer (mobile apenas) --}}
                        <button id="nav-toggle" onclick="floraNavToggle()"
                                class="xl:hidden flex flex-col gap-1.5 p-2 rounded-xl hover:bg-white/5 transition-colors"
                                aria-label="Menu de navegação">
                            <span id="nav-bar1" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                            <span id="nav-bar2" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                            <span id="nav-bar3" class="block w-5 h-px bg-[#C8A96E]/70 transition-all duration-300"></span>
                        </button>
                        {{-- Links (desktop) --}}
                        <nav class="hidden xl:flex items-center gap-1">
                            <a href="{{ route('plants.index') }}"
                               class="text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('plants.*') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                Catálogo
                            </a>
                            <a href="{{ route('quiz') }}"
                               class="text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('quiz') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                Quiz
                            </a>
                            @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                Diário
                            </a>
                            <a href="{{ route('alertas') }}"
                               class="relative text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('alertas') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                @php try { $unread = auth()->user()->unreadNotifications()->count(); } catch (\Exception $e) { $unread = 0; } @endphp
                                Alertas
                                @if($unread > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 text-[8px] bg-[#C8A96E] text-[#0E1A0B] rounded-full flex items-center justify-center font-bold">{{ $unread > 9 ? '9+' : $unread }}</span>
                                @endif
                            </a>
                            <a href="{{ route('conquistas') }}"
                               class="text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('conquistas') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                Conquistas
                            </a>
                            @endauth
                            <a href="{{ route('comunidade') }}"
                               class="text-xs uppercase tracking-widest font-medium hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('comunidade') ? 'text-[#C8A96E]' : 'text-[#DFD0B8]/70' }}">
                                Comunidade
                            </a>
                        </nav>
                    </div>

                    {{-- Logo centro --}}
                    <a href="/" class="shrink-0 text-center px-2">
                        <span class="font-serif text-2xl tracking-[0.2em] text-[#C8A96E] uppercase leading-none">Flora</span>
                        <span class="block text-[8px] uppercase tracking-[0.4em] text-[#7A8E72] mt-0.5">Botânica Interativa</span>
                    </a>

                    {{-- Dir: avatar de conta (auth) ou login/register (guest) --}}
                    <div class="flex items-center gap-2">
                        @auth
                        @php
                            $navUser    = auth()->user();
                            $navDisplay = $navUser->name;
                            $initials   = collect(explode(' ', $navDisplay))->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))->take(2)->join('');
                            $navUnread  = $unread ?? $navUser->unreadNotifications()->count();
                        @endphp

                        {{-- Avatar → dropdown de conta --}}
                        <div class="relative" id="profile-dropdown-wrap">
                            <button onclick="floraProfileToggle()"
                                    class="relative flex items-center gap-2 bg-[#131F11] border border-[#C8A96E]/40 hover:border-[#C8A96E]/80 rounded-full pl-1 pr-3 py-1 hover:bg-[#1A2E17] transition-all duration-200 group"
                                    aria-label="Conta">
                                @if($navUser->avatar_url)
                                    <img src="{{ $navUser->avatar_url }}" alt="{{ $navDisplay }}"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 border border-[#C8A96E]/30">
                                @else
                                    <span class="w-8 h-8 rounded-full bg-[#C8A96E] flex items-center justify-center text-[11px] font-bold text-[#0B160A] shrink-0">
                                        {{ $initials }}
                                    </span>
                                @endif
                                <span class="hidden md:block text-xs text-[#EDE0CC] group-hover:text-[#C8A96E] transition-colors max-w-[80px] truncate font-medium">{{ $navDisplay }}</span>
                                <svg class="hidden md:block w-2.5 h-2.5 text-[#C8A96E]/70 group-hover:text-[#C8A96E] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                @if($navUnread > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 text-[8px] bg-[#C8A96E] text-[#0E1A0B] rounded-full flex items-center justify-center font-bold">{{ $navUnread > 9 ? '9+' : $navUnread }}</span>
                                @endif
                            </button>

                            {{-- Dropdown de conta --}}
                            <div id="profile-dropdown"
                                 class="absolute right-0 top-[calc(100%+8px)] w-60 bg-[#0E1A0B] border border-[#C8A96E]/20 rounded-2xl p-2 shadow-[0_12px_48px_rgba(0,0,0,0.8)] z-50"
                                 style="display:none;">

                                {{-- Cabeçalho --}}
                                <div class="px-3 py-3 border-b border-[#C8A96E]/10 mb-1">
                                    <p class="text-[#EDE0CC] text-sm font-medium truncate">{{ $navDisplay }}</p>
                                    @if($navUser->nickname)
                                    <p class="text-[#C8A96E]/60 text-[10px] truncate">@{{ $navUser->nickname }}</p>
                                    @endif
                                    <p class="text-[#3A5E2D] text-[10px] truncate">{{ $navUser->email }}</p>
                                    @php $navLevel = \App\Support\Gamification::level($navUser->xp ?? 0); @endphp
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="text-sm">{{ $navLevel['icon'] }}</span>
                                        <span class="text-[9px] uppercase tracking-wider text-[#C8A96E]">{{ $navLevel['label'] }}</span>
                                        <span class="text-[#3A5E2D] text-[9px]">· {{ $navUser->xp ?? 0 }} XP</span>
                                    </div>
                                </div>

                                {{-- Opções de conta --}}
                                <a href="{{ route('perfil') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs text-[#9AA88E] hover:text-[#C8A96E] hover:bg-[#C8A96E]/8 transition-all duration-150 {{ request()->routeIs('perfil') ? 'text-[#C8A96E] bg-[#C8A96E]/8' : '' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="uppercase tracking-widest">Meu perfil</span>
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs text-[#9AA88E] hover:text-[#C8A96E] hover:bg-[#C8A96E]/8 transition-all duration-150 {{ request()->routeIs('profile.edit') ? 'text-[#C8A96E] bg-[#C8A96E]/8' : '' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span class="uppercase tracking-widest">Editar perfil</span>
                                </a>
                                @if($navUnread > 0)
                                <a href="{{ route('alertas') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs text-[#9AA88E] hover:text-[#C8A96E] hover:bg-[#C8A96E]/8 transition-all duration-150">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    <span class="uppercase tracking-widest">Alertas</span>
                                    <span class="ml-auto text-[9px] bg-[#C8A96E]/20 text-[#C8A96E] px-1.5 py-0.5 rounded-full">{{ $navUnread }}</span>
                                </a>
                                @endif

                                <div class="border-t border-[#C8A96E]/10 my-1"></div>

                                @php
                                    $adminEmails = array_filter(array_map('trim', explode(',', config('app.admin_email', env('ADMIN_EMAIL', '')))));
                                    $isAdmin = in_array($navUser->email, $adminEmails);
                                @endphp
                                @if($isAdmin)
                                <a href="/admin"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs text-[#C8A96E]/70 hover:text-[#C8A96E] hover:bg-[#C8A96E]/8 transition-all duration-150">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="uppercase tracking-widest">Painel admin</span>
                                    <span class="ml-auto text-[8px] bg-[#C8A96E]/15 text-[#C8A96E]/60 px-1.5 py-0.5 rounded-full border border-[#C8A96E]/20">ADM</span>
                                </a>
                                <div class="border-t border-[#C8A96E]/10 my-1"></div>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs text-[#9AA88E] hover:text-red-400 hover:bg-red-400/8 transition-all duration-150">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        <span class="uppercase tracking-widest">Sair</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @else
                        {{-- Guest --}}
                        <nav class="hidden xl:flex items-center gap-1">
                            <a href="{{ route('login') }}"
                               class="text-xs uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-2 rounded-full transition-all duration-200">
                                Entrar
                            </a>
                            <a href="{{ route('register') }}"
                               class="glass-gold text-[#C8A96E] hover:bg-[#C8A96E]/15 text-xs uppercase tracking-widest font-medium px-5 py-2 rounded-full transition-all duration-200">
                                Registrar
                            </a>
                        </nav>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile menu — apenas navegação (conta fica no dropdown do avatar) --}}
            <div id="mobile-menu"
                 class="xl:hidden glass rounded-2xl mt-2 overflow-hidden"
                 style="display:none;">
                <div class="p-2 space-y-0.5">
                    <p class="px-4 pt-2 pb-1 text-[8px] uppercase tracking-[0.4em] text-[#3A5E2D]">Navegar</p>
                    <a href="{{ route('plants.index') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('plants.*') ? 'text-[#C8A96E]' : '' }}">
                        <span>◎</span> Catálogo
                    </a>
                    <a href="{{ route('quiz') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('quiz') ? 'text-[#C8A96E]' : '' }}">
                        <span>✦</span> Quiz
                    </a>
                    @auth
                    <div class="border-t border-white/[0.06] my-1.5 mx-2"></div>
                    <p class="px-4 pb-1 text-[8px] uppercase tracking-[0.4em] text-[#3A5E2D]">Meu espaço</p>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-[#C8A96E]' : '' }}">
                        <span>🪴</span> Diário Verde
                    </a>
                    <a href="{{ route('alertas') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('alertas') ? 'text-[#C8A96E]' : '' }}">
                        <span>🔔</span> Alertas
                        @php $unreadMob = isset($navUnread) ? $navUnread : auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unreadMob > 0)
                        <span class="ml-auto w-5 h-5 text-[9px] bg-[#C8A96E] text-[#0E1A0B] rounded-full flex items-center justify-center font-bold">{{ $unreadMob > 9 ? '9+' : $unreadMob }}</span>
                        @endif
                    </a>
                    <a href="{{ route('conquistas') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('conquistas') ? 'text-[#C8A96E]' : '' }}">
                        <span>🏆</span> Conquistas
                    </a>
                    @endauth
                    <div class="border-t border-white/[0.06] my-1.5 mx-2"></div>
                    <p class="px-4 pb-1 text-[8px] uppercase tracking-[0.4em] text-[#3A5E2D]">Social</p>
                    <a href="{{ route('comunidade') }}"
                       class="flex items-center gap-3 text-sm uppercase tracking-widest font-medium text-[#DFD0B8]/70 hover:text-[#C8A96E] hover:bg-white/5 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('comunidade') ? 'text-[#C8A96E]' : '' }}">
                        <span>🌿</span> Comunidade
                    </a>
                    @auth
                    @else
                    <div class="border-t border-white/[0.06] my-1.5 mx-2"></div>
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
        </div>
    </header>

    <script>
        function floraNavToggle() {
            var menu = document.getElementById('mobile-menu');
            var b1 = document.getElementById('nav-bar1');
            var b2 = document.getElementById('nav-bar2');
            var b3 = document.getElementById('nav-bar3');
            if (!menu) return;
            var open = menu.style.display === 'block';
            menu.style.display = open ? 'none' : 'block';
            if (b1) { b1.style.transform = open ? '' : 'rotate(45deg) translateY(8px)'; }
            if (b2) { b2.style.opacity  = open ? '' : '0'; }
            if (b3) { b3.style.transform = open ? '' : 'rotate(-45deg) translateY(-8px)'; }
            // Fecha dropdown de perfil se abrir menu
            var dd = document.getElementById('profile-dropdown');
            if (dd && !open) dd.style.display = 'none';
        }

        function floraProfileToggle() {
            var dd = document.getElementById('profile-dropdown');
            if (!dd) return;
            var open = dd.style.display === 'block';
            dd.style.display = open ? 'none' : 'block';
            // Fecha menu mobile se abrir dropdown
            if (!open) {
                var menu = document.getElementById('mobile-menu');
                if (menu) menu.style.display = 'none';
            }
        }

        // Fecha dropdown ao clicar fora
        document.addEventListener('click', function(e) {
            var wrap = document.getElementById('profile-dropdown-wrap');
            var dd   = document.getElementById('profile-dropdown');
            if (wrap && dd && !wrap.contains(e.target)) {
                dd.style.display = 'none';
            }
        });
    </script>

    {{-- ── Barra de preview por token ──────────────────────────────────────── --}}
    @if(isset($adminPreview))
    <div id="adm-preview-bar"
         class="fixed bottom-0 inset-x-0 z-[500] flex justify-center pb-3 px-4 pointer-events-none">
        <div class="pointer-events-auto flex items-center gap-3 bg-[#0A0F09]/95 border border-violet-500/30
                    rounded-2xl px-4 py-2.5 shadow-[0_8px_40px_rgba(0,0,0,0.7)] backdrop-blur-sm
                    max-w-[min(100%,580px)] w-full">
            {{-- Indicador --}}
            <span class="shrink-0 w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
            {{-- Texto --}}
            <div class="flex-1 min-w-0">
                <p class="text-[10px] uppercase tracking-widest text-violet-400/80">Preview isolado</p>
                <p class="text-[#EDE0CC] text-xs font-medium truncate">
                    Visualizando como <strong>{{ $adminPreview['user_name'] }}</strong>
                    @if($adminPreview['expires_at'])
                    <span id="adm-preview-timer" class="text-[#7A8E72] font-normal ml-1"></span>
                    @endif
                </p>
            </div>
            {{-- Link para o perfil no admin --}}
            <a href="/admin/usuarios/{{ $adminPreview['user_id'] }}"
               target="_blank"
               class="shrink-0 text-[10px] uppercase tracking-widest text-[#7A8E72] hover:text-violet-400 transition-colors whitespace-nowrap">
                Admin ↗
            </a>
            {{-- Fechar aba --}}
            <button type="button" onclick="window.close()"
                    class="shrink-0 flex items-center gap-1.5 bg-violet-600 hover:bg-violet-500 text-white
                           text-[10px] uppercase tracking-widest font-semibold px-3 py-1.5 rounded-xl transition-all">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Fechar
            </button>
        </div>
    </div>
    <script>
    (function () {
        var token    = {{ json_encode($adminPreview['token']) }};
        var expiresAt = {{ json_encode($adminPreview['expires_at']) }};
        var timerEl  = document.getElementById('adm-preview-timer');

        // Contador regressivo
        if (timerEl && expiresAt) {
            function tick() {
                var diff = Math.max(0, Math.floor((new Date(expiresAt) - Date.now()) / 1000));
                if (diff === 0) { timerEl.textContent = '· expirado'; return; }
                var m = Math.floor(diff / 60), s = diff % 60;
                timerEl.textContent = '· expira em ' + m + ':' + (s < 10 ? '0' : '') + s;
                setTimeout(tick, 1000);
            }
            tick();
        }

        // Injeta token em todos os links e forms para manter a sessão de preview
        function injetarToken(url) {
            try {
                var u = new URL(url, location.origin);
                if (u.hostname !== location.hostname) return url;
                u.searchParams.set('_adm_preview', token);
                return u.toString();
            } catch (e) { return url; }
        }

        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (!a) return;
            var href = a.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('mailto:') || a.target === '_blank') return;
            var newHref = injetarToken(a.href);
            if (newHref !== a.href) {
                e.preventDefault();
                location.href = newHref;
            }
        }, true);

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form.querySelector('input[name="_adm_preview"]')) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = '_adm_preview';
                inp.value = token;
                form.appendChild(inp);
            }
        }, true);
    })();
    </script>
    @endif

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
            <div class="border-t border-white/[0.06] pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[#7A8E72] text-xs tracking-wide">© {{ date('Y') }} Flora · Projeto Acadêmico · ETEC João Belarmino</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacidade') }}" class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">Privacidade</a>
                    <span class="text-[#3A5E2D]">·</span>
                    <a href="{{ route('termos') }}" class="text-[9px] uppercase tracking-[0.3em] text-[#3A5E2D] hover:text-[#C8A96E] transition-colors">Termos</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Banner de cookies --}}
    <div id="flora-cookie-banner"
         class="fixed bottom-4 left-4 right-4 md:left-auto md:right-6 md:max-w-sm z-[300] glass rounded-2xl p-5 border border-[#C8A96E]/15 shadow-[0_8px_40px_rgba(0,0,0,0.5)]"
         style="display:none;">
        <p class="text-[#EDE0CC] text-sm font-medium mb-1">Cookies essenciais 🍪</p>
        <p class="text-[#7A8E72] text-xs leading-relaxed mb-4">
            Usamos apenas cookies necessários para manter seu login e proteger o site (sem rastreamento ou publicidade).
            <a href="{{ route('privacidade') }}#cookies" class="text-[#C8A96E] hover:underline">Saiba mais</a>
        </p>
        <div class="flex gap-2">
            <button onclick="floraCookieAccept()"
                    class="flex-1 bg-[#C8A96E] text-[#0B160A] text-[10px] uppercase tracking-widest font-bold py-2.5 rounded-full transition-all hover:bg-[#D4BA8A]">
                Entendido
            </button>
            <a href="{{ route('privacidade') }}"
               class="flex-1 glass text-[#7A8E72] hover:text-[#C8A96E] text-[10px] uppercase tracking-widest text-center py-2.5 rounded-full transition-all">
                Ler política
            </a>
        </div>
    </div>

    <script>
        (function() {
            if (!localStorage.getItem('flora_cookie_consent')) {
                var b = document.getElementById('flora-cookie-banner');
                if (b) b.style.display = 'block';
            }
        })();
        function floraCookieAccept() {
            localStorage.setItem('flora_cookie_consent', '1');
            var b = document.getElementById('flora-cookie-banner');
            if (b) b.style.display = 'none';
        }
    </script>

    @livewireScripts
    @auth
        <script src="{{ asset('js/push.js') }}"></script>
    @endauth
</body>
</html>
