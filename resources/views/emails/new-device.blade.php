<x-mail::message>
# Novo acesso detectado 🔒

Olá, {{ $user->name }}. Identificamos um acesso à sua conta Flora a partir de um dispositivo que ainda não conhecíamos.

<x-mail::panel>
**Quando:** {{ $when }}
**Dispositivo:** {{ $device }}
**Endereço IP:** {{ $ip }}
</x-mail::panel>

Se **foi você**, pode ignorar esta mensagem com tranquilidade.

Se **não reconhece** este acesso, recomendamos redefinir sua senha imediatamente:

<x-mail::button :url="route('password.request')" color="error">
Redefinir minha senha
</x-mail::button>

Cuidando da sua conta,
**Equipe Flora**

<x-mail::subcopy>
Este alerta de segurança é enviado apenas quando detectamos um acesso de um dispositivo novo.
</x-mail::subcopy>
</x-mail::message>
