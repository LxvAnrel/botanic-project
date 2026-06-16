<x-mail::message>
# Conta excluída

Olá, **{{ $userName }}**,

Sua conta na **Flora — Plataforma Botânica** foi **permanentemente excluída** em {{ now()->format('d/m/Y') }}.

Todos os seus dados foram removidos dos nossos servidores:

- Diário Verde e plantas salvas
- Histórico de cuidados
- Alertas e notificações
- Conquistas, badges e XP
- Dados de perfil

---

Se você quiser voltar a cultivar com a Flora, pode criar uma nova conta a qualquer momento. Será um prazer ter você de volta. 🌱

<x-mail::button :url="url('/register')" color="success">
Criar nova conta
</x-mail::button>

Atenciosamente,
**Equipe Flora** 🌿
</x-mail::message>
