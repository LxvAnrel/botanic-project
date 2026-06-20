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
    <style>
        #adm-drawer        { transform: translateX(-100%); transition: transform .25s cubic-bezier(.4,0,.2,1); }
        #adm-drawer.open   { transform: translateX(0); }
        #adm-backdrop      { opacity: 0; pointer-events: none; transition: opacity .25s; }
        #adm-backdrop.open { opacity: 1; pointer-events: auto; }
    </style>
</head>
<body class="min-h-screen bg-[#080F07]">

{{-- Ambient orbs --}}
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden" aria-hidden="true">
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-[#C8A96E]/5 blur-[120px]"></div>
    <div class="absolute bottom-0 -left-40 w-[400px] h-[400px] rounded-full bg-[#2D6A2D]/8 blur-[100px]"></div>
</div>

{{-- ══ MOBILE: Backdrop ══════════════════════════════════════════════════════ --}}
<div id="adm-backdrop"
     class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm md:hidden"
     onclick="admDrawerClose()"></div>

{{-- ══ MOBILE: Drawer (slide-over) ══════════════════════════════════════════ --}}
<aside id="adm-drawer"
       class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col md:hidden border-r border-white/[0.06]"
       style="background:#0A1309;">

    {{-- Header do drawer --}}
    <div class="flex items-center justify-between px-5 py-5 border-b border-white/[0.06]">
        <a href="/admin" onclick="admDrawerClose()">
            <span class="font-serif text-lg tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
            <span class="block text-[8px] uppercase tracking-[0.3em] text-[#3A5E2D] mt-0.5">Painel Admin</span>
        </a>
        <button onclick="admDrawerClose()"
                class="w-8 h-8 flex items-center justify-center rounded-xl text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        @include('admin._nav')
    </nav>

    {{-- Footer --}}
    <div class="px-3 py-4 border-t border-white/[0.06] space-y-1">
        @include('admin._sidebar-footer')
    </div>
</aside>

{{-- ══ DESKTOP: Sidebar fixa ══════════════════════════════════════════════════ --}}
<div class="hidden md:flex">
    <aside class="fixed inset-y-0 left-0 w-56 flex flex-col z-30 border-r border-white/[0.06]"
           style="background:#0A1309;">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-white/[0.06]">
            <a href="/admin" class="block">
                <span class="font-serif text-lg tracking-[0.2em] text-[#C8A96E] uppercase">Flora</span>
                <span class="block text-[8px] uppercase tracking-[0.3em] text-[#3A5E2D] mt-0.5">Painel Admin</span>
            </a>
        </div>
        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @include('admin._nav')
        </nav>
        {{-- Footer --}}
        <div class="px-3 py-4 border-t border-white/[0.06] space-y-1">
            @include('admin._sidebar-footer')
        </div>
    </aside>
</div>

{{-- ══ Main content ════════════════════════════════════════════════════════════ --}}
<div class="min-h-screen flex flex-col md:pl-56">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 flex items-center gap-3 px-4 md:px-6 py-3.5 border-b border-white/[0.06]"
            style="background:#0A1309;">

        {{-- Hamburger (mobile) --}}
        <button onclick="admDrawerOpen()"
                class="md:hidden flex flex-col gap-1.5 p-1.5 rounded-xl text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5 transition-all shrink-0"
                aria-label="Menu">
            <span class="block w-5 h-px bg-current transition-all"></span>
            <span class="block w-5 h-px bg-current transition-all"></span>
            <span class="block w-5 h-px bg-current transition-all"></span>
        </button>

        {{-- Back button contextual --}}
        @hasSection('back_url')
        <a href="@yield('back_url')"
           class="flex items-center gap-1.5 text-[#7A8E72] hover:text-[#C8A96E] transition-colors group shrink-0">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-[10px] uppercase tracking-widest hidden sm:inline">@yield('back_label', 'Voltar')</span>
        </a>
        <span class="text-white/10 text-lg shrink-0 hidden sm:inline">/</span>
        @endif

        {{-- Título / breadcrumb --}}
        <div class="flex-1 min-w-0">
            @hasSection('breadcrumb')
            <p class="text-[9px] uppercase tracking-widest text-[#3A5E2D] mb-0.5 hidden sm:block">@yield('breadcrumb')</p>
            @endif
            <h1 class="font-serif font-light text-base md:text-lg text-[#EDE0CC] truncate">@yield('title', 'Dashboard')</h1>
        </div>

        {{-- Ações --}}
        <div class="flex items-center gap-2 shrink-0">
            @if(session('admin_impersonating'))
            <span class="text-[9px] uppercase tracking-widest bg-red-900/30 text-red-400 border border-red-900/40 px-2 py-1 rounded-full hidden sm:inline">
                Impersonando
            </span>
            @endif
            @yield('header_actions')
            <span class="text-[10px] text-[#2A3A28] uppercase tracking-widest hidden lg:block">{{ now()->format('d/m/Y') }}</span>
        </div>
    </header>

    {{-- Alerts --}}
    <div class="px-4 md:px-6 pt-4">
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
            <pre class="text-xs text-[#9AA88E] whitespace-pre-wrap overflow-x-auto">{{ session('cmd_output') }}</pre>
        </div>
        @endif
    </div>

    {{-- Content --}}
    <main class="flex-1 px-4 md:px-6 py-4 pb-24 md:pb-12">
        @yield('content')
    </main>
</div>

{{-- ══ MOBILE: Bottom nav ══════════════════════════════════════════════════════ --}}
<nav class="fixed bottom-0 inset-x-0 z-30 md:hidden border-t border-white/[0.06]" style="background:#0A1309;">
    <div class="flex items-stretch">
        @php
        $bottomNav = [
            ['url' => '/admin',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Início',     'exact' => true],
            ['url' => '/admin/usuarios',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Usuários',   'exact' => false],
            ['url' => '/admin/plantas',   'icon' => 'M12 3v1m0 16v1M4.22 4.22l.707.707m12.02 12.02l.707.707M1 12h1m18 0h1M4.22 19.78l.707-.707M18.364 5.636l-.707-.707M12 7a5 5 0 100 10 5 5 0 000-10z', 'label' => 'Plantas',    'exact' => false],
            ['url' => '/admin/analytics', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Analytics', 'exact' => false],
            ['url' => '/admin/sistema',   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Sistema',   'exact' => false],
        ];
        @endphp
        @foreach($bottomNav as $bn)
        @php
            $path = ltrim($bn['url'], '/');
            $active = $bn['exact'] ? request()->is($path) : (request()->is($path) || request()->is($path . '/*'));
        @endphp
        <a href="{{ $bn['url'] }}"
           class="flex-1 flex flex-col items-center justify-center gap-1 py-3 transition-colors
                  {{ $active ? 'text-[#C8A96E]' : 'text-[#3A5E2D] hover:text-[#7A8E72]' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $bn['icon'] }}"/>
            </svg>
            <span class="text-[8px] uppercase tracking-widest">{{ $bn['label'] }}</span>
        </a>
        @endforeach
    </div>
</nav>

<script>
function admDrawerOpen() {
    document.getElementById('adm-drawer').classList.add('open');
    document.getElementById('adm-backdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function admDrawerClose() {
    document.getElementById('adm-drawer').classList.remove('open');
    document.getElementById('adm-backdrop').classList.remove('open');
    document.body.style.overflow = '';
}
// Fecha com Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') admDrawerClose();
});
</script>

</body>
</html>
