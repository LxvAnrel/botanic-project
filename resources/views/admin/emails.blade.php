@extends('admin.layout')
@section('title', 'Emails')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Formulario para enviar um email de teste para um endereco especifico --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-5">Enviar email de teste</h2>
        <form method="POST" action="/admin/emails/teste" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Destinatário</label>
                <input type="email" name="email" required placeholder="email@exemplo.com"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Versão do CTA</label>
                <select name="versao" class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 bg-[#131F11]">
                    <option value="1">V1 — 24h após cadastro</option>
                    <option value="2">V2 — 7 dias sem anotação</option>
                    <option value="3">V3 — 30 dias, último chamado</option>
                </select>
            </div>
            <button class="w-full bg-[#C8A96E] text-[#0B160A] text-xs uppercase tracking-widest font-semibold px-5 py-3 rounded-xl hover:bg-[#D4BA8A] transition-all">
                Enviar teste
            </button>
        </form>
    </div>

    {{-- Envia uma notificacao para todos os usuarios de uma vez --}}
    <div class="glass rounded-2xl p-6">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-1">Broadcast de notificação</h2>
        <p class="text-[10px] text-[#3A5E2D] mb-5">Aparece na página de alertas de todos os usuários.</p>
        <form method="POST" action="/admin/broadcast" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Título</label>
                <input type="text" name="titulo" required placeholder="Ex: Novidade no catálogo!"
                       class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Mensagem</label>
                <textarea name="mensagem" required rows="3" placeholder="Texto da notificação..."
                          class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 resize-none"></textarea>
            </div>
            <button class="w-full glass border border-[#C8A96E]/30 text-[#C8A96E] text-xs uppercase tracking-widest px-5 py-3 rounded-xl hover:border-[#C8A96E]/60 transition-all"
                    onclick="return confirm('Enviar notificação para TODOS os usuários?')">
                Enviar para todos
            </button>
        </form>
    </div>

    {{-- Email em massa segmentado por preferencia de notificacao --}}
    <div class="glass rounded-2xl p-6 lg:col-span-2">
        <h2 class="text-[9px] uppercase tracking-widest text-[#7A8E72] mb-1">Email em massa</h2>
        <p class="text-[10px] text-[#3A5E2D] mb-5">Só envia para usuários com notificações por email ativas.</p>
        <form method="POST" action="/admin/emails/massa" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Assunto</label>
                    <input type="text" name="assunto" required placeholder="Assunto do email"
                           class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Segmento</label>
                    <select name="segmento" class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 bg-[#131F11]">
                        <option value="todos">Todos os usuários</option>
                        <option value="com_plantas">Com plantas no diário</option>
                        <option value="sem_plantas">Sem plantas (nunca anotaram)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-[#7A8E72] mb-1.5">Mensagem</label>
                <textarea name="mensagem" required rows="5" placeholder="Texto do email..."
                          class="w-full glass border-white/[0.08] text-[#EDE0CC] text-sm px-4 py-2.5 rounded-xl focus:outline-none focus:border-[#C8A96E]/40 resize-none"></textarea>
            </div>
            <button class="glass border border-red-900/40 text-red-400 text-xs uppercase tracking-widest px-7 py-3 rounded-xl hover:border-red-900/70 transition-all"
                    onclick="return confirm('Enviar email em massa? Esta ação não pode ser desfeita.')">
                Enviar email em massa
            </button>
        </form>
    </div>

</div>

@endsection
