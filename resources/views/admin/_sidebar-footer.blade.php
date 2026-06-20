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

<a href="/dashboard"
   class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs uppercase tracking-widest
          text-[#7A8E72] hover:text-[#C8A96E] hover:bg-white/5 transition-all duration-150">
    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    Voltar ao site
</a>

<div class="px-3 pt-1 pb-0.5 flex items-center gap-2">
    <div class="w-5 h-5 rounded-full bg-[#C8A96E]/20 border border-[#C8A96E]/30 flex items-center justify-center text-[9px] font-bold text-[#C8A96E] shrink-0">
        {{ mb_strtoupper(mb_substr(auth()->user()->nickname ?? auth()->user()->name, 0, 1)) }}
    </div>
    <p class="text-[9px] text-[#2A3A28] truncate">{{ auth()->user()->nickname ?? auth()->user()->name }}</p>
</div>
