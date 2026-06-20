@extends('admin.layout')
@section('title', 'Usuários')
@section('header_actions')
    <span class="text-[10px] text-[#3A5E2D] uppercase tracking-widest">{{ $usuarios->total() }} registros</span>
@endsection

@section('content')

{{-- Busca --}}
<form method="GET" class="flex gap-2 mb-5">
    <input type="text" name="q" value="{{ $busca }}"
           placeholder="Nome ou email…"
           class="flex-1 glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
    <button class="glass border border-white/[0.08] text-[#C8A96E] text-xs uppercase tracking-widest px-4 py-2.5 rounded-xl hover:border-[#C8A96E]/40 transition-all shrink-0">
        Buscar
    </button>
    @if($busca)
    <a href="/admin/usuarios" class="glass border border-white/[0.05] text-[#7A8E72] text-xs uppercase tracking-widest px-4 py-2.5 rounded-xl hover:text-[#C8A96E] transition-all shrink-0">✕</a>
    @endif
</form>

{{-- ── Mobile: cards ─────────────────────────────────────────────────────── --}}
<div class="sm:hidden space-y-2">
    @foreach($usuarios as $u)
    <a href="/admin/usuarios/{{ $u->id }}"
       class="glass rounded-2xl p-4 flex items-center gap-4 hover:bg-white/[0.03] transition-colors">
        @if($u->avatar_url)
            <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
                 class="w-10 h-10 rounded-full object-cover border border-[#C8A96E]/20 shrink-0">
        @else
            <div class="w-10 h-10 rounded-full bg-[#C8A96E]/15 border border-[#C8A96E]/20 flex items-center justify-center text-sm font-bold text-[#C8A96E] shrink-0">
                {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <p class="text-[#EDE0CC] text-sm font-medium truncate">{{ $u->name }}</p>
            @if($u->nickname)<p class="text-[#C8A96E]/50 text-[10px] truncate">{{ '@' . $u->nickname }}</p>@endif
            <p class="text-[#3A5E2D] text-xs truncate">{{ $u->email }}</p>
        </div>
        <div class="text-right shrink-0">
            <p class="text-[#C8A96E] text-sm font-medium">{{ $u->plants_count }} 🌿</p>
            <p class="text-[#7A8E72] text-[10px]">{{ $u->xp ?? 0 }} XP</p>
        </div>
    </a>
    @endforeach
</div>

{{-- ── Desktop: tabela ───────────────────────────────────────────────────── --}}
<div class="hidden sm:block glass rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-white/[0.06]">
                <th class="text-left px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72]">Usuário</th>
                <th class="text-left px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72] hidden md:table-cell">Cadastro</th>
                <th class="text-center px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72]">Plantas</th>
                <th class="text-center px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72] hidden lg:table-cell">XP</th>
                <th class="text-center px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72] hidden lg:table-cell">Streak</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/[0.04]">
            @foreach($usuarios as $u)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        @if($u->avatar_url)
                            <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
                                 class="w-8 h-8 rounded-full object-cover border border-[#C8A96E]/20 shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-[#C8A96E]/15 border border-[#C8A96E]/20 flex items-center justify-center text-xs font-bold text-[#C8A96E] shrink-0">
                                {{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-[#EDE0CC] font-medium truncate">{{ $u->name }}</p>
                            <p class="text-[#3A5E2D] text-xs truncate">
                                @if($u->nickname)<span class="text-[#C8A96E]/50 mr-1">{{ '@' . $u->nickname }}</span>@endif
                                {{ $u->email }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-[#7A8E72] text-xs hidden md:table-cell">{{ $u->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-3.5 text-center text-[#C8A96E] text-sm">{{ $u->plants_count }}</td>
                <td class="px-5 py-3.5 text-center text-[#7A8E72] text-xs hidden lg:table-cell">{{ $u->xp ?? 0 }}</td>
                <td class="px-5 py-3.5 text-center text-[#7A8E72] text-xs hidden lg:table-cell">{{ $u->streak_days ?? 0 }}d</td>
                <td class="px-5 py-3.5 text-right">
                    <a href="/admin/usuarios/{{ $u->id }}" class="text-[9px] uppercase tracking-widest text-[#C8A96E] hover:underline">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($usuarios->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.06]">{{ $usuarios->links() }}</div>
    @endif
</div>

{{-- Paginação mobile --}}
@if($usuarios->hasPages())
<div class="sm:hidden mt-4">{{ $usuarios->links() }}</div>
@endif

@endsection
