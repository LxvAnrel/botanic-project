@extends('admin.layout')
@section('title', 'Plantas')

@section('content')

<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-3 flex-1 mr-4">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Buscar planta..."
               class="flex-1 glass border-white/[0.08] text-[#EDE0CC] placeholder-[#3A5E2D]/60 text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
        <button class="glass border border-white/[0.08] text-[#C8A96E] text-xs uppercase tracking-widest px-5 py-2.5 rounded-xl hover:border-[#C8A96E]/40 transition-all">Buscar</button>
    </form>
    <a href="/admin/plantas/criar"
       class="shrink-0 bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold px-5 py-2.5 rounded-xl hover:bg-[#D4BA8A] transition-all">
        + Adicionar
    </a>
</div>

<div class="glass rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-white/[0.06]">
                <th class="text-left px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72]">Nome</th>
                <th class="text-left px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72] hidden md:table-cell">Luz</th>
                <th class="text-center px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72]">Nos diários</th>
                <th class="text-center px-5 py-3.5 text-[9px] uppercase tracking-widest text-[#7A8E72] hidden lg:table-cell">Pet</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/[0.04]">
            @foreach($plantas as $p)
            <tr class="hover:bg-white/[0.02] transition-colors">
                <td class="px-5 py-4">
                    <p class="text-[#EDE0CC]">{{ $p->nome_popular }}</p>
                    <p class="text-[#3A5E2D] text-xs italic">{{ $p->nome_cientifico }}</p>
                </td>
                <td class="px-5 py-4 text-xs text-[#7A8E72] hidden md:table-cell">
                    {{ ['sol_pleno' => '☀ Sol', 'meia_sombra' => '◑ Meia', 'sombra' => '● Sombra'][$p->habitat_luz] ?? '—' }}
                </td>
                <td class="px-5 py-4 text-center text-[#C8A96E] text-sm">{{ $p->users_count }}</td>
                <td class="px-5 py-4 text-center text-xs hidden lg:table-cell">
                    {{ $p->toxica_pets ? '<span class="text-red-400">✕</span>' : '<span class="text-[#7AC77A]">✓</span>' }}
                </td>
                <td class="px-5 py-4 text-right flex items-center justify-end gap-3">
                    <a href="/admin/plantas/{{ $p->id }}/editar" class="text-[9px] uppercase tracking-widest text-[#C8A96E] hover:underline">Editar</a>
                    <form method="POST" action="/admin/plantas/{{ $p->id }}"
                          onsubmit="return confirm('Remover {{ $p->nome_popular }}?')" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-[9px] uppercase tracking-widest text-red-400/60 hover:text-red-400 transition-colors">Remover</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($plantas->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.06]">{{ $plantas->links() }}</div>
    @endif
</div>

@endsection
