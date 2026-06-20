@extends('admin.layout')
@section('title', 'Usuários')
@section('header_actions')
    <span class="text-[10px] text-[#3A5E2D] uppercase tracking-widest">{{ $usuarios->total() }} registros</span>
@endsection

@section('content')

{{-- Busca --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="q" value="{{ $busca }}"
           placeholder="Buscar por nome ou email..."
           class="flex-1 glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
    <button class="glass border border-white/[0.08] text-[#C8A96E] text-xs uppercase tracking-widest px-5 py-2.5 rounded-xl hover:border-[#C8A96E]/40 transition-all">Buscar</button>
    @if($busca)<a href="/admin/usuarios" class="glass border border-white/[0.05] text-[#7A8E72] text-xs uppercase tracking-widest px-5 py-2.5 rounded-xl hover:text-[#C8A96E] transition-all">Limpar</a>@endif
</form>

{{-- Tabela --}}
<div class="glass rounded-2xl overflow-hidden">
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
                <td class="px-5 py-4">
                    <p class="text-[#EDE0CC] font-medium">{{ $u->name }}</p>
                    <p class="text-[#3A5E2D] text-xs">{{ $u->email }}</p>
                </td>
                <td class="px-5 py-4 text-[#7A8E72] text-xs hidden md:table-cell">{{ $u->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-4 text-center text-[#C8A96E] text-sm">{{ $u->plants_count }}</td>
                <td class="px-5 py-4 text-center text-[#7A8E72] text-xs hidden lg:table-cell">{{ $u->xp ?? 0 }}</td>
                <td class="px-5 py-4 text-center text-[#7A8E72] text-xs hidden lg:table-cell">{{ $u->streak_days ?? 0 }}d</td>
                <td class="px-5 py-4 text-right">
                    <a href="/admin/usuarios/{{ $u->id }}" class="text-[9px] uppercase tracking-widest text-[#C8A96E] hover:underline mr-3">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($usuarios->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.06]">{{ $usuarios->links() }}</div>
    @endif
</div>

@endsection
