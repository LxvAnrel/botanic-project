@extends('layouts.app')

@section('title', 'Política de Privacidade')

@section('content')
<div class="max-w-3xl mx-auto px-5 md:px-8 py-12 md:py-20">

    <div class="mb-10 pb-8 border-b border-white/[0.06]">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Legal</p>
        <h1 class="font-serif font-light text-4xl md:text-5xl text-[#EDE0CC] mb-3">Política de <em class="text-[#C8A96E]">Privacidade</em></h1>
        <p class="text-[#7A8E72] text-sm">Última atualização: 16 de junho de 2026</p>
    </div>

    <div class="glass rounded-3xl p-6 md:p-10 mb-6 border border-[#C8A96E]/10">
        <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-3">Natureza do projeto</p>
        <p class="text-[#9AA88E] text-sm leading-relaxed">
            A <strong class="text-[#EDE0CC]">Flora — Plataforma Botânica Interativa</strong> é um projeto <strong class="text-[#EDE0CC]">estritamente acadêmico</strong>, desenvolvido como trabalho escolar de Biologia por um aluno do 3.º ano da <strong class="text-[#EDE0CC]">ETEC João Belarmino</strong>. Não há fins comerciais, publicitários ou lucrativos. Os dados coletados servem exclusivamente para o funcionamento das funcionalidades educativas da plataforma.
        </p>
    </div>

    <div class="space-y-8 text-[#9AA88E] text-sm leading-relaxed">

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">1. Quem somos</h2>
            <p>O controlador dos dados é o aluno responsável pelo projeto, vinculado à ETEC João Belarmino. Para entrar em contato: <a href="mailto:{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}" class="text-[#C8A96E] hover:underline">{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}</a>.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">2. Dados que coletamos</h2>
            <ul class="space-y-2">
                @foreach([
                    ['Nome e e-mail', 'Fornecidos no cadastro, usados para identificação e envio de alertas de plantas.'],
                    ['Plantas salvas e cuidados registrados', 'Conteúdo que você cria voluntariamente no Diário Verde.'],
                    ['Data e hora de ações', 'Para calcular sequências (streak), alertas e pontuação XP.'],
                    ['Dispositivo e navegador (via push)', 'Somente se você autorizar notificações push; usado exclusivamente para enviar os alertas.'],
                    ['Cookies de sessão', 'Necessários para manter o login ativo e proteger contra ataques CSRF.'],
                ] as [$dado, $motivo])
                <li class="flex gap-3">
                    <span class="text-[#C8A96E] mt-1 shrink-0">·</span>
                    <span><strong class="text-[#EDE0CC]">{{ $dado }}:</strong> {{ $motivo }}</span>
                </li>
                @endforeach
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">3. Finalidade do tratamento</h2>
            <p class="mb-3">Os dados são usados para:</p>
            <ul class="space-y-1.5">
                @foreach([
                    'Fornecer as funcionalidades da plataforma (Diário Verde, alertas, conquistas)',
                    'Enviar notificações de cuidados com plantas que você cadastrou',
                    'Calcular pontuação, níveis e badges do sistema de gamificação',
                    'Demonstração e avaliação acadêmica do projeto na ETEC João Belarmino',
                ] as $item)
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">◎</span>{{ $item }}</li>
                @endforeach
            </ul>
            <p class="mt-3">Não utilizamos seus dados para publicidade, perfil comportamental, análise de mercado nem compartilhamento com terceiros.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">4. Base legal (LGPD)</h2>
            <p class="mb-3">O tratamento dos seus dados se fundamenta em:</p>
            <ul class="space-y-2">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">Consentimento (Art. 7º, I):</strong> obtido no momento do cadastro, para uso das funcionalidades e recebimento de e-mails de alerta.</span></li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">Execução de contrato (Art. 7º, V):</strong> dados necessários para operar os serviços que você solicitou ao criar a conta.</span></li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">Legítimo interesse acadêmico (Art. 7º, IX):</strong> finalidade educacional documentada e restrita ao escopo do projeto.</span></li>
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">5. Seus direitos (LGPD, Art. 18)</h2>
            <p class="mb-3">Você tem direito a, a qualquer momento:</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach([
                    ['Acesso', 'Saber quais dados seus estão armazenados'],
                    ['Correção', 'Atualizar nome, e-mail ou senha'],
                    ['Exclusão', 'Apagar sua conta e todos os dados (em até 30 dias)'],
                    ['Portabilidade', 'Receber seus dados em formato legível'],
                    ['Revogação do consentimento', 'Retirar sua autorização a qualquer momento'],
                    ['Informação', 'Saber com quem compartilhamos seus dados (ninguém)'],
                ] as [$titulo, $desc])
                <div class="glass rounded-xl p-3">
                    <p class="text-[#EDE0CC] text-xs font-medium mb-0.5">{{ $titulo }}</p>
                    <p class="text-[10px] text-[#7A8E72]">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
            <p class="mt-4">Para exercer qualquer direito, acesse <strong class="text-[#EDE0CC]">Perfil → Editar Perfil</strong> ou envie e-mail para <a href="mailto:{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}" class="text-[#C8A96E] hover:underline">{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}</a>. Respondemos em até <strong class="text-[#EDE0CC]">15 dias</strong>.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">6. Compartilhamento de dados</h2>
            <p>Não vendemos, alugamos nem compartilhamos seus dados com terceiros para fins comerciais. Os únicos serviços externos que podem processar dados são:</p>
            <ul class="mt-3 space-y-2">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">Railway.app</strong> (hospedagem): servidor onde o sistema roda. Políticas em railway.app/legal.</span></li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">Resend</strong> (envio de e-mail): usado exclusivamente para e-mails transacionais (alertas, boas-vindas). Políticas em resend.com/legal/privacy-policy.</span></li>
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">7. Retenção e exclusão</h2>
            <p>Seus dados ficam armazenados enquanto sua conta existir. Ao solicitar exclusão de conta:</p>
            <ul class="mt-3 space-y-1.5">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">1.</span>Sua conta fica pausada por 30 dias (período de arrependimento).</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">2.</span>Após 30 dias, todos os dados são permanentemente apagados dos nossos servidores.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">3.</span>E-mails enviados anteriormente não podem ser recuperados dos servidores do Resend.</li>
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">8. Cookies</h2>
            <p class="mb-3">Utilizamos apenas cookies necessários:</p>
            <ul class="space-y-2">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">flora_session:</strong> mantém seu login ativo. Expira ao fechar o navegador.</span></li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span><span><strong class="text-[#EDE0CC]">XSRF-TOKEN:</strong> proteção contra ataques CSRF. Técnico e obrigatório.</span></li>
            </ul>
            <p class="mt-3">Não usamos cookies de rastreamento, publicidade ou analytics.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">9. Segurança</h2>
            <p>Adotamos as seguintes medidas de segurança: senhas armazenadas com hash bcrypt, comunicação via HTTPS/TLS, proteção CSRF em todos os formulários, headers de segurança HTTP (X-Frame-Options, HSTS, CSP), rate limiting em login e registro.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">10. Contato e DPO</h2>
            <p>Encarregado de dados (DPO): aluno responsável pelo projeto — ETEC João Belarmino, trabalho escolar de Biologia.<br>
            E-mail: <a href="mailto:{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}" class="text-[#C8A96E] hover:underline">{{ config('flora.mail.contato.address', 'contato@florabotanic.site') }}</a></p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">11. Alterações nesta política</h2>
            <p>Qualquer alteração será comunicada por e-mail e/ou por aviso na plataforma. O uso contínuo da Flora após a comunicação implica aceitação das mudanças.</p>
        </section>

    </div>

    <div class="mt-12 pt-8 border-t border-white/[0.06] flex flex-wrap gap-4">
        <a href="{{ route('termos') }}" class="glass-gold text-[#C8A96E] text-xs uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
            Ler Termos de Uso →
        </a>
        <a href="/" class="glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
            Voltar ao início
        </a>
    </div>

</div>
@endsection
