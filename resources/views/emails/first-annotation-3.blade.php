@extends('emails.layouts.flora')

@section('body')

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
  <td style="padding-bottom:28px;text-align:center;">
    <div style="display:inline-block;width:64px;height:64px;background-color:rgba(200,169,110,0.08);border:1px solid rgba(200,169,110,0.2);border-radius:50%;text-align:center;line-height:64px;font-size:28px;">🍂</div>
  </td>
</tr>
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <h1 style="margin:0;font-family:'Georgia',serif;font-size:24px;font-weight:400;color:#EDE0CC;letter-spacing:1px;">Seu jardim ainda tem um lugar reservado para você</h1>
  </td>
</tr>
<tr>
  <td style="padding-bottom:32px;text-align:center;">
    <p style="margin:0;font-size:13px;color:#7A8E72;font-style:italic;">30 dias. Uma última palavra.</p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:24px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      Olá, {{ $user->name }}. Há um mês você abriu as portas do seu jardim digital — e ele continua esperando pela primeira planta.
    </p>
  </td>
</tr>
<tr>
  <td style="padding-bottom:28px;">
    <p style="margin:0;font-size:14px;line-height:1.9;color:#9AA88E;">
      Não mandamos muitos e-mails. Este é nosso último chamado antes de deixar você em paz. Se o momento não chegou ainda, tudo bem — a Flora continua aqui quando você estiver pronto.
    </p>
  </td>
</tr>

{{-- O que está esperando --}}
<tr>
  <td style="padding-bottom:28px;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:rgba(200,169,110,0.04);border:1px solid rgba(200,169,110,0.08);border-radius:12px;">
      <tr><td style="padding:20px 24px 8px;">
        <p style="margin:0;font-size:9px;letter-spacing:4px;color:#C8A96E;text-transform:uppercase;">O que ainda está esperando por você</p>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:13px;color:#C8A96E;vertical-align:top;padding-top:1px;">💧</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Alertas inteligentes de cuidados</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Rega e adubação na hora certa, sem esquecer</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:13px;color:#C8A96E;vertical-align:top;padding-top:1px;">🏅</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Conquistas e nível na comunidade</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Histórico de cuidados que evolui com você</p></td>
        </tr></table>
      </td></tr>
      <tr><td style="padding:10px 24px 20px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0"><tr>
          <td style="width:28px;font-size:13px;color:#C8A96E;vertical-align:top;padding-top:1px;">🌿</td>
          <td><p style="margin:0;font-size:13px;color:#EDE0CC;">Seu catálogo pessoal de plantas</p><p style="margin:2px 0 0;font-size:11px;color:#7A8E72;">Tudo o que você tem, cuida e quer um dia ter</p></td>
        </tr></table>
      </td></tr>
    </table>
  </td>
</tr>

{{-- CTA principal --}}
<tr>
  <td style="padding-bottom:8px;text-align:center;">
    <a href="{{ route('dashboard') }}" class="btn" style="display:inline-block;background-color:#C8A96E;color:#0B160A;font-size:11px;letter-spacing:3px;text-transform:uppercase;font-weight:700;padding:14px 36px;border-radius:100px;text-decoration:none;">Começar agora, de uma vez →</a>
  </td>
</tr>
<tr>
  <td style="padding-top:20px;padding-bottom:4px;text-align:center;">
    <p style="margin:0;font-size:12px;color:#3A5E2D;font-style:italic;line-height:1.7;">
      Não quer mais receber nossos e-mails? Você pode desativar nas configurações do seu perfil.
    </p>
  </td>
</tr>
</table>

@endsection
