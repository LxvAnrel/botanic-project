@extends('layouts.app')

@section('title', 'Termos de Uso')

@section('content')
<div class="max-w-3xl mx-auto px-5 md:px-8 py-12 md:py-20">

    <div class="mb-10 pb-8 border-b border-white/[0.06]">
        <p class="text-[9px] uppercase tracking-[0.5em] text-[#7A8E72] mb-4">— Legal</p>
        <h1 class="font-serif font-light text-4xl md:text-5xl text-[#EDE0CC] mb-3">Termos de <em class="text-[#C8A96E]">Uso</em></h1>
        <p class="text-[#7A8E72] text-sm">Última atualização: 16 de junho de 2026</p>
    </div>

    <div class="glass rounded-3xl p-6 md:p-10 mb-6 border border-[#C8A96E]/10">
        <p class="text-[9px] uppercase tracking-[0.4em] text-[#C8A96E] mb-3">Leia antes de usar</p>
        <p class="text-[#9AA88E] text-sm leading-relaxed">
            A <strong class="text-[#EDE0CC]">Flora — Plataforma Botânica Interativa</strong> é um projeto <strong class="text-[#EDE0CC]">exclusivamente acadêmico</strong>, sem fins lucrativos, desenvolvido como trabalho de conclusão de curso técnico na <strong class="text-[#EDE0CC]">ETEC João Belarmino</strong>. Ao criar uma conta, você concorda com estes termos.
        </p>
    </div>

    <div class="space-y-8 text-[#9AA88E] text-sm leading-relaxed">

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">1. Aceitação dos Termos</h2>
            <p>Ao se cadastrar ou utilizar qualquer funcionalidade da Flora, você declara ter lido, compreendido e concordado com estes Termos de Uso e com a <a href="{{ route('privacidade') }}" class="text-[#C8A96E] hover:underline">Política de Privacidade</a>. Se não concordar, não utilize a plataforma.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">2. Natureza e finalidade do serviço</h2>
            <p class="mb-3">A Flora é uma plataforma educativa que permite:</p>
            <ul class="space-y-1.5">
                @foreach([
                    'Explorar um catálogo de plantas com informações botânicas',
                    'Realizar um quiz de recomendação de espécies',
                    'Montar um Diário Verde pessoal com plantas salvas',
                    'Registrar cuidados (rega, adubação, poda)',
                    'Receber alertas de cuidados por notificação push ou e-mail',
                    'Participar de um sistema de gamificação com XP, níveis e badges',
                ] as $item)
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">◎</span>{{ $item }}</li>
                @endforeach
            </ul>
            <p class="mt-3">O sistema é oferecido gratuitamente e sem garantia de disponibilidade contínua, pois trata-se de ambiente de desenvolvimento acadêmico.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">3. Elegibilidade</h2>
            <p>Qualquer pessoa com acesso à internet pode criar uma conta. Ao se cadastrar, você declara que as informações fornecidas são verdadeiras. Não há restrição de idade definida pela plataforma, mas o uso por menores de 13 anos deve ter consentimento dos responsáveis legais, conforme o Estatuto da Criança e do Adolescente (ECA).</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">4. Conta de usuário</h2>
            <ul class="space-y-2">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Você é responsável pela confidencialidade da sua senha.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Cada pessoa deve ter apenas uma conta.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Você pode solicitar a exclusão da conta a qualquer momento em <strong class="text-[#EDE0CC]">Perfil → Editar Perfil → Excluir conta</strong>.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Reservamo-nos o direito de encerrar contas que violem estes termos.</li>
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">5. Uso aceitável</h2>
            <p class="mb-3">É proibido utilizar a Flora para:</p>
            <ul class="space-y-1.5">
                @foreach([
                    'Cadastrar informações falsas ou enganosas',
                    'Tentar invadir ou comprometer a segurança da plataforma',
                    'Usar scripts automatizados ou bots para criar contas ou interagir com o sistema',
                    'Qualquer finalidade comercial ou de coleta de dados de terceiros',
                ] as $item)
                <li class="flex gap-3"><span class="text-red-400/50 shrink-0">✕</span>{{ $item }}</li>
                @endforeach
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">6. Propriedade intelectual</h2>
            <p>O código, design, textos e elementos visuais da Flora são de autoria do aluno desenvolvedor, com fins acadêmicos. As informações botânicas do catálogo são baseadas em fontes públicas de botânica. Nomes científicos e populares das plantas são de domínio público.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">7. Isenção de responsabilidade</h2>
            <p class="mb-3">Por tratar-se de projeto acadêmico:</p>
            <ul class="space-y-2">
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>As informações botânicas são fornecidas com fins educativos e não substituem orientação de especialistas em botânica, agronomia ou veterinária.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Não garantimos disponibilidade ininterrupta — o serviço pode ficar fora do ar para manutenção ou encerrar ao final do projeto acadêmico.</li>
                <li class="flex gap-3"><span class="text-[#C8A96E] shrink-0">·</span>Não nos responsabilizamos por perda de dados decorrente de falhas técnicas, embora adotemos boas práticas de segurança.</li>
            </ul>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">8. Privacidade e LGPD</h2>
            <p>O tratamento de dados pessoais é regulado pela nossa <a href="{{ route('privacidade') }}" class="text-[#C8A96E] hover:underline">Política de Privacidade</a>, em conformidade com a Lei Geral de Proteção de Dados (Lei n.º 13.709/2018). Você pode solicitar a exclusão total dos seus dados a qualquer momento.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">9. Notificações e e-mails</h2>
            <p>Ao criar uma conta, você concorda em receber e-mails transacionais relacionados ao funcionamento do serviço (boas-vindas, alertas de cuidados, conquistas). Esses e-mails não são de marketing. Você pode desativar alertas de push a qualquer momento nas configurações do navegador.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">10. Modificações nos termos</h2>
            <p>Estes termos podem ser atualizados. Qualquer alteração relevante será comunicada por e-mail com pelo menos 7 dias de antecedência. O uso continuado após o prazo implica aceitação.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">11. Lei aplicável e foro</h2>
            <p>Estes termos são regidos pelas leis brasileiras. Eventuais disputas serão resolvidas no foro da comarca onde se situa a ETEC João Belarmino, com preferência por resolução amigável.</p>
        </section>

        <section>
            <h2 class="font-serif text-xl text-[#EDE0CC] mb-3">12. Contato</h2>
            <p>Dúvidas, solicitações ou reclamações: <a href="mailto:{{ config('flora.mail.ola.address', 'contato@flora.app') }}" class="text-[#C8A96E] hover:underline">{{ config('flora.mail.ola.address', 'contato@flora.app') }}</a></p>
        </section>

    </div>

    <div class="mt-12 pt-8 border-t border-white/[0.06] flex flex-wrap gap-4">
        <a href="{{ route('privacidade') }}" class="glass-gold text-[#C8A96E] text-xs uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
            Ler Política de Privacidade →
        </a>
        <a href="/" class="glass text-[#7A8E72] hover:text-[#C8A96E] text-xs uppercase tracking-widest px-6 py-3 rounded-full transition-all duration-200">
            Voltar ao início
        </a>
    </div>

</div>
@endsection
