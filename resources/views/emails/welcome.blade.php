<x-mail::message>
# Olá, {{ $user->name }} 🌿

Que bom ter você na **Flora**. Sua conta está pronta — agora é só começar a cultivar seu cantinho verde.

Aqui você pode:

- **Explorar o catálogo** e conhecer plantas, cuidados e curiosidades
- **Montar seu Diário Verde** salvando as plantas que você cultiva
- **Receber alertas de poda** na hora certa de cada espécie

<x-mail::button :url="route('plants.index')">
Explorar o catálogo
</x-mail::button>

Sempre que precisar, é só voltar e abrir seu Diário Verde.

Com carinho,
**Equipe Flora**

<x-mail::subcopy>
Você recebeu este email porque criou uma conta na Flora. Se não foi você, pode ignorar esta mensagem com segurança.
</x-mail::subcopy>
</x-mail::message>
