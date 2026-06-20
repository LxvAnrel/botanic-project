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
    <div class="px-5 py-6 border-b border-white/[0.06]">
        <a href="/admin" class="block">
            <span class="font-serif text-lg tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
            <span class="block text-[8px] uppercase tracking-[0.3em] text-[#3A5E2D] mt-0.5">Admin</span>
        </a>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5">
        @php
        $nav = [
            ['url' => '/admin',            'label' => 'Dashboard',  'icon' => '◈'],
            ['url' => '/admin/usuarios',   'label' => 'Usuários',   'icon' => '◎'],
            ['url' => '/admin/plantas',    'label' => 'Plantas',    'icon' => '🌿'],
            ['url' => '/admin/emails',     'label' => 'Emails',     'icon' => '✉'],
            ['url' => '/admin/sistema',    'label' => 'Sistema',    'icon' => '⚙'],
            ['url' => '/admin/analytics',  'label' => 'Analytics',  'icon' => '◉'],
        ];
        @endphp
        @foreach($nav as $item)
        @php $active = request()->is(ltrim($item['url'], '/')) || (request()->is(ltrim($item['url'], '/') . '/*')); @endphp
        <a href="{{ $item['url'] }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs uppercase tracking-widest transition-all duration-150
                  {{ $active ? 'bg-[#C8A96E]/12 text-[#C8A96E]' : 'text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5' }}">
            <span class="text-sm w-4 text-center">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- Footer --}}
    <div class="px-4 py-4 border-t border-white/[0.06] space-y-2">
        @if(session('admin_impersonating'))
        <form method="POST" action="/admin/sair-impersonacao">
            @csrf
            <button class="w-full text-left text-[10px] uppercase tracking-widest text-red-400 hover:text-red-300 px-2 py-1.5 transition-colors">
                ← Sair da impersonação
            </button>
        </form>
        @endif
        <a href="/dashboard" class="block text-[10px] uppercase tracking-widest text-[#3A5E2D] hover:text-[#7A8E72] px-2 py-1 transition-colors">← Voltar ao site</a>
        <p class="text-[9px] text-[#2A3A28] px-2">{{ auth()->user()->name }}</p>
    </div>
</aside>

{{-- Main --}}
<div class="flex-1 flex flex-col min-h-screen overflow-x-hidden z-10">

    {{-- Topbar --}}
    <header class="shrink-0 flex items-center justify-between px-6 py-4 border-b border-white/[0.06]" style="background:#0A1309;">
        <h1 class="font-serif font-light text-xl text-[#EDE0CC]">@yield('title', 'Dashboard')</h1>
        <div class="flex items-center gap-3">
            @if(session('admin_impersonating'))
            <span class="text-[9px] uppercase tracking-widest bg-red-900/30 text-red-400 border border-red-900/40 px-3 py-1 rounded-full">Modo impersonação</span>
            @endif
            <span class="text-[10px] text-[#3A5E2D] uppercase tracking-widest">{{ now()->format('d/m/Y') }}</span>
        </div>
    </header>

    {{-- Alerts --}}
    <div class="px-6 pt-4">
        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-[#2D6A2D]/20 border border-[#2D6A2D]/40 rounded-xl text-sm text-[#7AC77A]">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-900/20 border border-red-900/40 rounded-xl text-sm text-red-400">{{ session('error') }}</div>
        @endif
        @if(session('cmd_output'))
        <div class="mb-4 px-4 py-3 bg-[#0B1A0A] border border-white/[0.08] rounded-xl">
            <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D] mb-2">Output do comando</p>
            <pre class="text-xs text-[#9AA88E] whitespace-pre-wrap">{{ session('cmd_output') }}</pre>
        </div>
        @endif
    </div>

    {{-- Content --}}
    <main class="flex-1 px-6 py-4 pb-10">
        @yield('content')
    </main>
</div>

</body>
</html>
