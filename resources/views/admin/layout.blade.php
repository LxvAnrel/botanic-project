<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin · @yield('title', 'Painel') · Flora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex" style="background:#080F07;">

{{-- Ambient orbs --}}
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-[#C8A96E]/5 blur-[120px]"></div>
    <div class="absolute bottom-0 -left-40 w-[400px] h-[400px] rounded-full bg-[#2D6A2D]/8 blur-[100px]"></div>
</div>

{{-- Sidebar --}}
<aside class="shrink-0 w-56 min-h-screen flex flex-col z-10 border-r border-white/[0.06]" style="background:#0A1309;">
    {{-- Logo --}}
    <div class="px-5 py-5 border-b border-white/[0.06]">
        <a href="/admin" class="block">
            <span class="font-serif text-lg tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
            <span class="block text-[8px] uppercase tracking-[0.3em] text-[#3A5E2D] mt-0.5">Painel Admin</span>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @php
        $nav = [
            ['url' => '/admin',            'label' => 'Dashboard',  'icon' => '◈', 'exact' => true],
            ['url' => '/admin/usuarios',   'label' => 'Usuários',   'icon' => '◎', 'exact' => false],
            ['url' => '/admin/plantas',    'label' => 'Plantas',    'icon' => '🌿', 'exact' => false],
            ['url' => '/admin/emails',     'label' => 'Emails',     'icon' => '✉', 'exact' => false],
            ['url' => '/admin/sistema',    'label' => 'Sistema',    'icon' => '⚙', 'exact' => false],
            ['url' => '/admin/analytics',  'label' => 'Analytics',  'icon' => '◉', 'exact' => false],
        ];
        @endphp
        @foreach($nav as $item)
        @php
            $path = ltrim($item['url'], '/');
            $active = $item['exact']
                ? request()->is($path)
                : (request()->is($path) || request()->is($path . '/*'));
        @endphp
        <a href="{{ $item['url'] }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs uppercase tracking-widest transition-all duration-150
                  {{ $active ? 'bg-[#C8A96E]/12 text-[#C8A96E]' : 'text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5' }}">
            <span class="text-sm w-4 text-center">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- Footer --}}
    <div class="px-3 py-4 border-t border-white/[0.06] space-y-1">
        @if(session('admin_impersonating'))
        <form method="POST" action="/admin/sair-impersonacao">
            @csrf
            <button class="w-full flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs uppercase tracking-widest
                           text-red-400 hover:text-red-300 hover:bg-red-400/8 transition-all duration-150">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9l-6 6m0 0l6 6m-6-6h16"/>
                </svg>
                Sair impersonação
            </button>
        </form>
        @endif

        {{-- Voltar ao site --}}
        <a href="/dashboard"
           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs uppercase tracking-widest
                  text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5 transition-all duration-150">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Voltar ao site
        </a>

        {{-- Usuário logado --}}
        <div class="px-3 pt-1 pb-0.5 flex items-center gap-2">
            <div class="w-5 h-5 rounded-full bg-[#C8A96E]/20 border border-[#C8A96E]/30 flex items-center justify-center text-[9px] font-bold text-[#C8A96E] shrink-0">
                {{ mb_strtoupper(mb_substr(auth()->user()->nickname ?? auth()->user()->name, 0, 1)) }}
            </div>
            <p class="text-[9px] text-[#2A3A28] truncate">{{ auth()->user()->nickname ?? auth()->user()->name }}</p>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="flex-1 flex flex-col min-h-screen overflow-x-hidden z-10">

    {{-- Topbar --}}
    <header class="shrink-0 flex items-center gap-4 px-6 py-3.5 border-b border-white/[0.06]" style="background:#0A1309;">

        {{-- Botão voltar contextual --}}
        @hasSection('back_url')
        <a href="@yield('back_url')"
           class="flex items-center gap-1.5 text-[#7A8E72] hover:text-[#C8A96E] transition-colors group shrink-0">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-[10px] uppercase tracking-widest">@yield('back_label', 'Voltar')</span>
        </a>
        <span class="text-white/10 text-lg shrink-0">/</span>
        @endif

        {{-- Breadcrumb / título --}}
        <div class="flex-1 min-w-0">
            @hasSection('breadcrumb')
            <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D] mb-0.5">@yield('breadcrumb')</p>
            @endif
            <h1 class="font-serif font-light text-lg text-[#EDE0CC] truncate">@yield('title', 'Dashboard')</h1>
        </div>

        {{-- Ações do topo --}}
        <div class="flex items-center gap-3 shrink-0">
            @if(session('admin_impersonating'))
            <span class="text-[9px] uppercase tracking-widest bg-red-900/30 text-red-400 border border-red-900/40 px-3 py-1 rounded-full">
                Impersonando
            </span>
            @endif
            @yield('header_actions')
            <span class="text-[10px] text-[#2A3A28] uppercase tracking-widest hidden md:block">{{ now()->format('d/m/Y') }}</span>
        </div>
    </header>

    {{-- Alerts --}}
    <div class="px-6 pt-4">
        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-[#2D6A2D]/20 border border-[#2D6A2D]/40 rounded-xl text-sm text-[#7AC77A]">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-900/20 border border-red-900/40 rounded-xl text-sm text-red-400">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('cmd_output'))
        <div class="mb-4 px-4 py-3 bg-[#0B1A0A] border border-white/[0.08] rounded-xl">
            <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D] mb-2">Output</p>
            <pre class="text-xs text-[#9AA88E] whitespace-pre-wrap">{{ session('cmd_output') }}</pre>
        </div>
        @endif
    </div>

    {{-- Content --}}
    <main class="flex-1 px-6 py-4 pb-12">
        @yield('content')
    </main>
</div>

</body>
</html>
