<x-mail::message>
# Solicitação de exclusão de conta

Olá, **{{ $user->name }}**,

Recebemos uma solicitação para excluir sua conta na **Flora — Plataforma Botânica**.

Sua conta ficará disponível por **30 dias** a partir de hoje. Caso mude de ideia, clique no botão abaixo para cancelar a exclusão e recuperar todos os seus dados:

<x-mail::button :url="$reactivationUrl" color="success">
Reativar minha conta
</x-mail::button>

**Este link expira em {{ $expiresAt }}.**

---

Se você não reativar sua conta até essa data, o seguinte será **permanentemente excluído**:

- Seu Diário Verde e todas as plantas salvas
- Histórico de cuidados (regas, adubações, podas)
- Alertas e notificações
- Conquistas, badges e XP acumulado
- Dados de perfil e preferências

Se não foi você quem solicitou a exclusão, reative sua conta imediatamente e altere sua senha.

Atenciosamente,
**Equipe Flora** 🌿
</x-mail::message>
